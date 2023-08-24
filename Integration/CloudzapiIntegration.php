<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticCloudzapiBundle\Integration;
use Mautic\CoreBundle\Form\Type\YesNoButtonGroupType;
use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CloudzapiIntegration.
 */
class CloudzapiIntegration extends AbstractIntegration
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'Cloudzapi';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getIcon()
    {
        return 'plugins/MauticCloudzapiBundle/Assets/img/cloudzapi.png';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getSecretKeys()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRequiredKeyFields()
    {
        return [
            'api_key' => 'mautic.plugin.cloudzapi.api_key',
            'sender_id' => 'mautic.plugin.cloudzapi.sender_id',
        ];
    }

    /**
     * @return array
     */
    public function getFormSettings()
    {
        return [
            'requires_callback'      => false,
            'requires_authorization' => false,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

     /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ('features' == $formArea) {
            
            $builder->add(
                'disable_trackable_urls',
                YesNoButtonGroupType::class,
                [
                    'label' => 'mautic.sms.config.form.sms.disable_trackable_urls',
                    'attr'  => [
                        'tooltip' => 'mautic.sms.config.form.sms.disable_trackable_urls.tooltip',
                    ],
                    'data'=> !empty($data['disable_trackable_urls']) ? true : false,
                ]
            );
            
        }
    }
}
