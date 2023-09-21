<?php
/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author      Jan Kozak <galvani78@gmail.com>
 */

namespace MauticPlugin\MauticCloudzapiBundle\Transport;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticCloudzapiBundle\Api\AbstractSmsApi;
use Monolog\Logger;
use GuzzleHttp\Client;

class CloudzapiTransport extends AbstractSmsApi
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var IntegrationHelper
     */
    protected $integrationHelper;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    private $api_key;

    /**
     * @var string
     */
    private $sender_id;

    /**
     * @var bool
     */
    protected $connected;

    /**
     * @param IntegrationHelper $integrationHelper
     * @param Logger            $logger
     * @param Client            $client
     */
    public function __construct(IntegrationHelper $integrationHelper, Logger $logger, Client $client)
    {
        $this->integrationHelper = $integrationHelper;
        $this->logger = $logger;
        $this->client = $client;
        $this->connected = false;
    }

         /**
     * @return bool
     */
    public function getDisableTrackableUrls()
    {
      
        return true;
    }

        /**
     * Convert a non-tracked url to a tracked url.
     *
     * @param string $url
     *
     * @return string
     */
    public function convertToTrackedUrl($url, array $clickthrough = [])
    {
       

        return $url;//$this->pageTrackableModel->generateTrackableUrl($trackable, $clickthrough, true);
    }

    /**
     * @param Lead   $contact
     * @param string $content
     *
     * @return bool|string
     */
    public function sendSms(Lead $contact, $content)
    {

    

        $number = $contact->getLeadPhoneNumber();
        if (empty($number)) {
            return false;
        }

        try {
            $number = substr($this->sanitizeNumber($number), 1);
        } catch (NumberParseException $e) {
            $this->logger->addInfo('Invalid number format. ', ['exception' => $e]);
            return $e->getMessage();
        }

        try {
            if (!$this->connected && !$this->configureConnection()) {
                throw new \Exception("Cloudzapi CloudZapi is not configured properly.");
            }

            $content = $this->sanitizeContent($content, $contact);
            if (empty($content)) {
                throw new \Exception('Message content is Empty.');
            }

            $response = $this->send($number, $content);
            $this->logger->addInfo("Cloudzapi CloudZapi request succeeded. ", ['response' => $response]);
            return true;
        } catch (\Exception $e) {
            $this->logger->addError("Cloudzapi CloudZapi request failed. ", ['exception' => $e]);
            return $e->getMessage();
        }
    }

    /**
     * @param integer   $number
     * @param string    $content
     * 
     * @return array
     * 
     * @throws \Exception
     */
    protected function send($number, $content)
    {

    sleep(rand(5,12));



if (str_contains($content, 'SEND_IMAGE') || str_contains($content, 'SEND_VIDEO') || str_contains($content, 'SEND_PDF') || str_contains($content, 'SEND_AUDIO')) {

    if (str_contains($content, 'SEND_IMAGE')){
       
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $match);
        $url_media=$match[0];
        $this->logger->addInfo("url_media ", ['response' => $url_media]);
        $data= [
            "numbers" => [
                $number
                ], 
            "options" => [
                    "delay" => 3000, 
                    "presence" => "composing" 
                ], 
            "mediaMessage" => [
                        "caption"=>"",
                        "mediaType"=>"image",
                        "fileName"=>"image",
                        "urlOrId" => $url_media[0]
                    ] 
        ]; 
        $url='https://api.cloudzapi.com/'.$this->sender_id.'/message/sendMedia';
        $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'apikey: '.$this->api_key.'',
            'Content-Length: ' . strlen($data_string))
            );
            $res=curl_exec($ch);
            curl_close($ch);
       
    } 

    if (str_contains($content, 'SEND_VIDEO')){
       
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $match);
        $url_media=$match[0];
        $this->logger->addInfo("url_media ", ['response' => $url_media]);
        $data= [
            "numbers" => [
                $number
                ], 
            "options" => [
                    "delay" => 3000, 
                    "presence" => "composing" 
                ], 
            "mediaMessage" => [
                        "caption"=>"",
                        "mediaType"=>"video",
                        "fileName"=>"video",
                        "urlOrId" => $url_media[0]
                    ] 
        ]; 
        $url='https://api.cloudzapi.com/'.$this->sender_id.'/message/sendMedia';
        $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'apikey: '.$this->api_key.'',
            'Content-Length: ' . strlen($data_string))
            );
            $res=curl_exec($ch);
            curl_close($ch);
       
    } 

    if (str_contains($content, 'SEND_PDF')){
       
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $match);
        $url_media=$match[0];
        $this->logger->addInfo("url_media ", ['response' => $url_media]);
        $data= [
            "numbers" => [
                $number
                ], 
            "options" => [
                    "delay" => 3000, 
                    "presence" => "composing" 
                ], 
            "mediaMessage" => [
                        "caption"=>"",
                        "mediaType"=>"document",
                        "fileName"=>"document.pdf",
                        "urlOrId" => $url_media[0]
                    ] 
        ]; 
        $url='https://api.cloudzapi.com/'.$this->sender_id.'/message/sendMedia';
        $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'apikey: '.$this->api_key.'',
            'Content-Length: ' . strlen($data_string))
            );
            $res=curl_exec($ch);
            curl_close($ch);
        
    } 
    if (str_contains($content, 'SEND_AUDIO')){
       
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $match);
        $url_media=$match[0];
        $this->logger->addInfo("url_media ", ['response' => $url_media]);
        $data= [
            "numbers" => [
                $number
                ], 
            "options" => [
                    "delay" => 3000, 
                    "presence" => "recording" 
                ], 
            "whatsappAudio" => [
                       
                        "audio" => $url_media[0]
                    ] 
        ]; 
        $url='https://api.cloudzapi.com/'.$this->sender_id.'/message/sendWhatsAppAudio';
        $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'apikey: '.$this->api_key.'',
            'Content-Length: ' . strlen($data_string))
            );
            $res=curl_exec($ch);
            curl_close($ch);
     
    } 

  

}else{

    $url='https://api.cloudzapi.com/'.$this->sender_id.'/message/sendText';

    $data= [
        "numbers" => [
            $number
           ], 
        "options" => [
                 "delay" => 3000, 
                 "presence" => "composing" 
              ], 
        "textMessage" => [
                    "text" => $content
                 ] 
     ]; 
    
    $data_string = json_encode($data);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 360);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'apikey: '.$this->api_key.'',
      'Content-Length: ' . strlen($data_string))
    );
    echo $res=curl_exec($ch);
    curl_close($ch);


}


       
    }

    /**
     * @param string $number
     *
     * @return string
     *
     * @throws NumberParseException
     */
    protected function sanitizeNumber($number)
    {
        $util = PhoneNumberUtil::getInstance();
        $parsed = $util->parse($number, 'BR');

        return $util->format($parsed, PhoneNumberFormat::E164);
    }

    /**
     * @return bool
     */
    protected function configureConnection()
    {
        $integration = $this->integrationHelper->getIntegrationObject('Cloudzapi');
        if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
            $keys = $integration->getDecryptedApiKeys();
             if (empty($keys['api_key']) || empty($keys['sender_id'])) {
            //if (empty($keys['api_key']) ) {
	    return false;
            }
            $this->api_key = $keys['api_key'];
            $this->sender_id = $keys['sender_id'];
            $this->connected = true;
        }
        return $this->connected;
    }

    /**
     * @param string $content
     * @param Lead   $contact
     *
     * @return string
     */
    protected function sanitizeContent(string $content, Lead $contact) {
        return strtr($content, array(
            '{contact_title}' => $contact->getTitle(),
            '{contact_firstname}' => $contact->getFirstname(),
            '{contact_lastname}' => $contact->getLastname(),
            '{contact_lastname}' => $contact->getName(),
            '{contact_company}' => $contact->getCompany(),
            '{contact_email}' => $contact->getEmail(),
            '{contact_address1}' => $contact->getAddress1(),
            '{contact_address2}' => $contact->getAddress2(),
            '{contact_city}' => $contact->getCity(),
            '{contact_state}' => $contact->getState(),
            '{contact_country}' => $contact->getCountry(),
            '{contact_zipcode}' => $contact->getZipcode(),
            '{contact_location}' => $contact->getLocation(),
            '{contact_phone}' => $contact->getLeadPhoneNumber(),
        ));
    }

  
}
