<?php

    class Comwrap_InvoiceEmails_Model_Observer
    {
        const XML_PATH_EMAIL_PDF_TO = 'sales_email/invoice/pdf_to';
        const XML_PATH_EMAIL_PDF_TEMPLATE = 'sales_email/invoice/pdf_template';
        const XML_PATH_EMAIL_PDF_SENDER = 'sales_email/invoice/pdf_sender';

        public function sendEmail(Varien_Event_Observer $observer)
        {
            $invoice = $observer->getEvent()->getInvoice();

            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_PDF_TEMPLATE);

            $destinations = array_map('trim', explode(',', Mage::getStoreConfig(self::XML_PATH_EMAIL_PDF_TO)));

            // get the invoice pdf
            $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice))->render();

            // create the attachment
            $mp = new Zend_Mime_Part($pdf);
            $mp->encoding = Zend_Mime::ENCODING_BASE64;
            $mp->type = Zend_Mime::TYPE_OCTETSTREAM;
            $mp->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
            $mp->filename = Mage::helper('sales')->__('Invoice') . ' ' . $invoice->getIncrementId() . '.pdf';

            $mailer = Mage::getModel('comwrap_invoiceemails/mailer');

            $mailer->setAttachment($mp);

            $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_PDF_SENDER));

            $mailer->setTemplateId($templateId);

            foreach($destinations as $destination)
            {
                $mailer->addEmailInfo(Mage::getModel('core/email_info')->addTo($destination));
            }

            try
            {
                $mailer->send();
            }
            catch(Exception $e)
            {

            }
        }
    }