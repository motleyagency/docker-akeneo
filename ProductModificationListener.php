<?php
namespace Acme\Bundle\AppBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;

class ProductModificationListener
{

    public function __construct()
    {
    }

    public function onPostSave(GenericEvent $event)
    {
        // Check if this is a product call
        $subject = $event->getSubject();
        $isProductModificationCall = is_subclass_of(
          $subject,
          'Pim\Component\Catalog\Model\ProductInterface'
        );

        // Notify server upon request
        if ($isProductModificationCall) {
          $url = 'http://server.com/path';
          $data = json_encode($subject);
          $options = array(
            'http' => array(
              'header'  => "Content-type: application/json\r\n",
              'method'  => 'POST',
              'content' => $data
            )
          );
          $context  = stream_context_create($options);
          $result = file_get_contents('http://url-to-server/?productId=' . $subject, false, $context);
        }
    }
}
  
