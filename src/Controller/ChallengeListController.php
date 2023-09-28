<?php

namespace App\Controller;

use App\Entity\Challenge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// augmenter les points, enlever les 'en cours' des 'à faire', 

class ChallengeListController extends AbstractController
{
    #[Route("/challenge-list", name: "app_challenge_list")]
    public function main()
    {
        $userCurrentChallenges = [];
        $userFinishChallenges = [];
        $challenges = $this->getDoctrine()->getRepository(Challenge::class)->findAll();

        if ($this->getUser()) {
            $userCurrentChallenges = $this->getCurrentChallenges();
        }
        if ($this->getUser()) {
            $userFinishChallenges = $this->getFinishChallenges();
        }

        return $this->render('challenge-list.html.twig', ['challengeList' => $challenges, 'userCurrentChallenges' => $userCurrentChallenges, 'finishChallenges' => $userFinishChallenges]);
    }

    public function getCurrentChallenges()
    {
        $user = $this->getUser();
        $userChallenges = $user->getUserChallenges();
        $userChallengesArray = $userChallenges->toArray();
        foreach ($userChallengesArray as $key => $userChallengeArray) {
            if ($userChallengeArray->getStatus() != 1) {
                unset($userChallengesArray[$key]);
            }
        }
        return $userChallengesArray;
    }

    public function getFinishChallenges()
    {
        $user = $this->getUser();
        $userChallenges = $user->getUserChallenges();
        $userChallengesArray = $userChallenges->toArray();
        foreach ($userChallengesArray as $key => $userChallengeArray) {
            if ($userChallengeArray->getStatus() != 2) {
                unset($userChallengesArray[$key]);
            }
        }
        return $userChallengesArray;
    }
}