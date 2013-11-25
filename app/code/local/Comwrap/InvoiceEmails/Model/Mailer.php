<?php

    class Comwrap_InvoiceEmails_Model_Mailer extends Mage_Core_Model_Email_Template_Mailer
    {
        /**
         * Send all emails from email list
         * @see self::$_emailInfos
         *
         * @return Mage_Core_Model_Email_Template_Mailer
         */
        public function send()
        {
            $emailTemplate = Mage::getModel('core/email_template');

            // Send all emails from corresponding list
            while (!empty($this->_emailInfos)) {

                ////////////////////////////////////////////////////////////////////////////////

                if($this->getAttachment())
                {
                    $emailTemplate->getMail()->addAttachment($this->getAttachment());
                }

                ////////////////////////////////////////////////////////////////////////////////

                $emailInfo = array_pop($this->_emailInfos);
                // Handle "Bcc" recepients of the current email
                $emailTemplate->addBcc($emailInfo->getBccEmails());
                // Set required design parameters and delegate email sending to Mage_Core_Model_Email_Template
                $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $this->getStoreId()))
                    ->sendTransactional(
                        $this->getTemplateId(),
                        $this->getSender(),
                        $emailInfo->getToEmails(),
                        $emailInfo->getToNames(),
                        $this->getTemplateParams(),
                        $this->getStoreId()
                    );
            }
            return $this;
        }
    }