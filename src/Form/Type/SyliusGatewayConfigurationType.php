<?php
declare(strict_types=1);

namespace Lpweb\SetefiSyliusPlugin\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class SyliusGatewayConfigurationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->add('id', TextType::class);
        $builder->add('password', TextType::class);
        $builder->add('sandbox', ChoiceType::class);
    }
}