<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\NotificationBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use MajidMvulle\Bundle\NotificationBundle\Constants;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailInterface;
use MajidMvulle\Bundle\NotificationBundle\Interfaces\MailRepositoryInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

/**
 * Class MailerCommand.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MailerCommand extends ContainerAwareCommand
{
    /**
     * @var int
     */
    private $startTime;

    /**
     * @var int
     */
    private $maxRuntime;

    /**
     * @var int
     */
    private $idleTime;

    /**
     * @var int
     */
    private $delay;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var PHPMailer
     */
    private $mailerService;

    /**
     * @var TwigEngine
     */
    private $twig;

    /**
     * @var string
     */
    private $cssFilePath;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var CssToInlineStyles
     */
    private $cssToInlineStyles;

    /**
     * @var OutputInterface
     */
    private $output;

    protected function configure(): void
    {
        $this->setName('majidmvulle:notification:mailer')
            ->setDescription('Command to send emails from a queue')
            ->addOption('max-runtime', 'r', InputOption::VALUE_REQUIRED, 'The maximum runtime in seconds.', 900)
            ->addOption('delay', 'd', InputOption::VALUE_REQUIRED, 'Delay between each email in miliseconds.', 250)
            ->addOption('idle-time', null, InputOption::VALUE_REQUIRED, 'Time to sleep when the queue ran out of messages.', 2)
            ->addOption('batch-size', null, InputOption::VALUE_REQUIRED, 'Emails retrieved from database at once', 10)
            ->addOption('validate-mx', null, InputOption::VALUE_NONE, 'Validate if recipient domain contain mx addresses')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->startTime = time();
        $this->maxRuntime = (int) $input->getOption('max-runtime');

        if ($this->maxRuntime <= 0) {
            throw new \InvalidArgumentException('The maximum runtime must be greater than zero.');
        }

        $this->idleTime = (int) $input->getOption('idle-time');

        if ($this->idleTime <= 0) {
            throw new \InvalidArgumentException('Time to sleep when idling must be greater than zero.');
        }

        $this->delay = (int) $input->getOption('delay');

        if ($this->delay <= 0) {
            throw new \InvalidArgumentException('Delay between emails must be greater than zero.');
        }

        $this->batchSize = (int) $input->getOption('batch-size');

        if ($this->batchSize <= 0) {
            throw new \InvalidArgumentException('Batch size must be greater than zero.');
        }

        $container = $this->getContainer();
        $this->entityClass = $container->getParameter('majidmvulle.notification.mailer.entity_class');
        $entityManagerId = $container->getParameter('majidmvulle.notification.mailer.entity_manager');
        $this->entityManager = $container->get($entityManagerId);

        $this->mailerService = new PHPMailer($this->output->isVerbose());
        $this->mailerService->SMTPDebug = $this->output->isVerbose();
        $this->mailerService->isSMTP();
        $this->mailerService->Host = $container->getParameter('majidmvulle.notification.mailer.smtp_url');
        $this->mailerService->SMTPAuth = true;
        $this->mailerService->Username = $container->getParameter('majidmvulle.notification.mailer.smtp_username');
        $this->mailerService->Password = $container->getParameter('majidmvulle.notification.mailer.smtp_password');
        $this->mailerService->SMTPSecure = $container->getParameter('majidmvulle.notification.mailer.smtp_encryption');
        $this->mailerService->Port = $container->getParameter('majidmvulle.notification.mailer.smtp_port');

        $this->twig = $container->get('templating');
        $this->serializer = $container->get('jms_serializer');
        $this->cssFilePath = $container->getParameter('majidmvulle.notification.mailer.css_file_path');
        $this->cssToInlineStyles = new CssToInlineStyles();

        $this->output = $output;
        $this->doMail();
    }

    private function doMail()
    {
        /** @var MailRepositoryInterface $repository */
        $repository = $this->entityManager->getRepository($this->entityClass);

        while (time() - $this->startTime < $this->maxRuntime) {
            $pendingMails = $repository->findPending($this->batchSize);

            if (null === $pendingMails) {
                sleep($this->idleTime);
                continue;
            }

            while ($mail = array_shift($pendingMails)) {
                $this->sendMail($mail);
                usleep($this->delay * 1000);
            }

            $this->entityManager->clear();

            gc_collect_cycles();
            sleep(2);
        }
    }

    private function sendMail(MailInterface $mail): void
    {
        $this->output->writeln(sprintf('Started %s.', $mail));
        $mail->setState(Constants::STATE_SENDING);

        if (Constants::STATE_SENDING === $mail->getState()) {
            try {
                $this->entityManager->flush();
                $this->send($mail);
                $mail->setState(Constants::STATE_SENT);
            } catch (\Exception $exception) {
                $mail->setState(Constants::STATE_FAILED);
                $mail->setException($this->serializer->serialize(FlattenException::create($exception), 'json'));
                $this->output->writeln(sprintf('Failed %s (%s).', $mail, $exception->getMessage()));
            }
        }

        $this->entityManager->flush();
        $this->output->writeln(sprintf('Finished %s.', $mail));
    }

    private function send(MailInterface $mail): void
    {
        $this->mailerService->clearCCs();
        $this->mailerService->clearBCCs();
        $this->mailerService->clearAllRecipients();
        $this->mailerService->clearAttachments();
        $this->mailerService->clearCustomHeaders();
        $this->mailerService->clearReplyTos();
        $this->mailerService->Body = '';
        $this->mailerService->AltBody = '';
        $this->mailerService->From = '';
        $this->mailerService->FromName = '';

        $this->output->writeln(sprintf('Sending %s.', $mail));

        $template = $mail->getTemplate();
        $content = $this->twig->render($template['path'], $template['parameters']);
        $this->mailerService->Subject = $mail->getSubject();
        $this->mailerService->isHTML((bool) ($template['is_html']));
        $this->mailerService->From = $mail->getSender();
        $this->mailerService->addAddress($mail->getRecipient());

        if ($mail->getCc()) {
            foreach ($mail->getCc() as $cc) {
                $this->mailerService->addCC($cc);
            }
        }

        if ($mail->getBcc()) {
            foreach ($mail->getBcc() as $bcc) {
                $this->mailerService->addBCC($bcc);
            }
        }

        if (true === (bool) ($template['is_html'])) {
            $css = file_get_contents($this->cssFilePath);
            $content = $this->cssToInlineStyles->convert($content, $css);
        }

        $this->mailerService->Body = $content;

        $this->mailerService->send();

        $this->output->writeln(sprintf('Sent %s.', $mail));
    }
}
