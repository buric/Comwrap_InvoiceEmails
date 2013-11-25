<?php

    $template = Mage::getModel('adminhtml/email_template');
    $template->setTemplateCode('New Invoice - PDF');
    $template->setTemplateSubject('New Invoice');
    $template->setTemplateText('You have new invoice.');

    try
    {
        $template->save();
        if($template->getId())
        {
            Mage::getConfig()->saveConfig(Comwrap_InvoiceEmails_Model_Observer::XML_PATH_EMAIL_PDF_TEMPLATE, $template->getId());
        }
    }
    catch (Exception $e)
    {

    }