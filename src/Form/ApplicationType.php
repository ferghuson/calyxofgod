<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    protected function config($label, $placeholder='', $option=[])
    {
        return array_merge(
            ['label'=>$label, 'attr'=>array('placeholder'=>$placeholder)],
            $option
        );
    }
}