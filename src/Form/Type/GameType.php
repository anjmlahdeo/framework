<?php

// src/Form/Type/GameType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Rois\Dice\Game;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dices', ChoiceType::class, [
            'choices'  => [
                '1' => 1,
                '2' => 2
            ],
        ])
        ->add('play', SubmitType::class, ['label' => 'Play Game']);
    }
}
