<?php

namespace WalleePayment\Components\Controller;

abstract class Frontend extends \Enlight_Controller_Action
{
    public function preDispatch()
    {
        $this->get('template')->addTemplateDir($this->container->getParameter('wallee_payment.plugin_dir') . '/Resources/views/');
        $this->get('snippets')->addConfigDir($this->container->getParameter('wallee_payment.plugin_dir') . '/Resources/snippets/');

        parent::preDispatch();
    }

    /**
     * Sends the data received by calling the given path to the browser.
     *
     * @param string $path
     */
    protected function download(\Wallee\Sdk\Model\RenderedDocument $document)
    {
        $this->Front()->Plugins()->ViewRenderer()->setNoRender();

        $this->Response()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', 'application/pdf', true)
            ->setHeader('Content-Disposition', 'attachment; filename=' . $document->getTitle() . '.pdf')
            ->setHeader('Content-Description', $document->getTitle());
        $this->Response()->setBody(base64_decode($document->getData()));

        $this->Response()->sendHeaders();
        session_write_close();
        $this->Response()->outputBody();
    }

    /**
     *
     * @param map[string,string]|\Wallee\Sdk\Model\DatabaseTranslatedString $string
     * @return string
     */
    protected function translate($string)
    {
        return $this->get('wallee_payment.translator')->translate($string);
    }
}
