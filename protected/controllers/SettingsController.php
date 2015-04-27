<?php

class SettingsController extends Controller {

    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    private $_settings_keys;
    private $_settings = array();
    private $_presettings = array();

    public function init() {
        parent::init();

        $this->_settings_keys = array(
            'mailing_list',
            'remote_token'
        );

        $this->setSettings();
    }

    private function setSettings() {
        $this->_presettings = array('files' => 0, 'cur_folder' => 0, 'fileavatarWidth' => 150, 'fileavatarHeight' => 150);



        $this->_settings = array(
            'mail' => array(
                'mailing_list' => array('textarea', '', array('cols'=>90, 'rows'=>6)),
            ),
            "other" => array(
                'remote_token' => array('text', '', array('size'=>'20')),
            ),
        );

        Yii::app()->clientScript->registerScript(
                'myHideFunc', '$("#mail_transport").change(function(){

			   	$("#confirm_emails").change(function(){

					if($(this).attr("checked") )
					{
						$("#confirm_email_template").parents(".row").show();
						$("#confirm_email_subject").parents(".row").show();
					}
					else
					{
						$("#confirm_email_template").parents(".row").hide();
						$("#confirm_email_subject").parents(".row").hide();

					}
				});

				$("#confirm_emails").change();

				$("#allow_recovery").change(function(){

					if($(this).attr("checked") )
					{
						$("#password_recovery_subject").parents(".row").show();
						$("#password_recovery_template").parents(".row").show();
					}
					else
					{
						$("#password_recovery_subject").parents(".row").hide();
						$("#password_recovery_template").parents(".row").hide();
					}
				});

				$("#allow_recovery").change();


				if($(this).val()=="mailTransport")
					{
						//nothing to show and hide here
					}
					else
					{

					}

					if($(this).val()=="smtpTransport")
					{

						$("#smtp_host").parents(".row").show();
						$("#smtp_port").parents(".row").show();
						$("#smtp_encryption_level").parents(".row").show();
						$("#smtp_requires_authorization").parents(".row").show();

						if($("#smtp_requires_authorization").attr("checked"))
						{
							$("#smtp_username").parents(".row").show();
							$("#smtp_password").parents(".row").show();
						}
					}
					else
					{
						$("#smtp_host").parents(".row").hide();
						$("#smtp_port").parents(".row").hide();
						$("#smtp_encryption_level").parents(".row").hide();
						$("#smtp_requires_authorization").parents(".row").hide();
						$("#sendmail_command").parents(".row").hide();
						$("#smtp_username").parents(".row").hide();
						$("#smtp_password").parents(".row").hide();
					}

					if($(this).val()=="sendmailTransport")
					{
						$("#sendmail_command").parents(".row").show();
					}
					else
					{
						$("#sendmail_command").parents(".row").hide();
					}

			});

			$("#mail_transport").change();

			$("#smtp_requires_authorization").change(function(){

				if($(this).attr("checked") && $("#mail_transport").val() == "smtpTransport")
				{

					$("#smtp_username").parents(".row").show();
					$("#smtp_password").parents(".row").show();
				}
				else
				{
					$("#smtp_username").parents(".row").hide();
					$("#smtp_password").parents(".row").hide();
				}
			});

			$("#smtp_requires_authorization").change();
		   ', CClientScript::POS_READY
        );
    }

    public function actionIndex() {

        if (sizeof($_POST) > 0) {
            $available_settings = $this->_settings_keys;

            foreach ($available_settings as $setting) {
                if (!is_string($setting))
                    continue;
                Yii::app()->par->$setting = (isset($_POST[$setting])) ? $_POST[$setting] : '';
            }
            Yii::app()->user->setFlash('message', Yii::t("settings", "Settings are saved"));
            $this->refresh();
        }

        $this->render('index', array(
            'settings' => $this->_settings,
        ));
    }

}
