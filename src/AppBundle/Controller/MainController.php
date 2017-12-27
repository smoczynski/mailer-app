<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
     //   $process = new Process('../bin/console test');
     //   $process->run();

//        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
//        }

//        $process->clearOutput();
//
//        echo $process->getOutput();

    //    $process->start();

//        foreach ($process as $type => $data) {
//            if ($process::OUT === $type) {
//                echo "\nRead from stdout: ".$data;
//            } else { // $process::ERR === $type
//                echo "\nRead from stderr: ".$data;
//            }
//        }



        return;
    }

    /**
     * @Route("/email/add", name="email_create")
     * @Template()
     */
    public function addEmailAction()
    {
        return;
    }
}
