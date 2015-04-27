<?php

class CronCommand extends CConsoleCommand
{
    public function run($args)
    {
        $this->removeFromPause();
        
        // Get site.
        $condition = new CDbCriteria();
        $condition->compare('state', 'ok');
        $condition->addCondition('date_add(checked, INTERVAL check_interval HOUR) < now()');
        $condition->order = ' checked asc';

        $site = Site::model()->find($condition);
        if ($site) {
            echo "Site ".$site->id. " rehashing.";
            $site->rehash();
        } else {
            Yii::app()->l->log('CRON Idle run ');
            echo "No sites for now";
        }
    }

    private function removeFromPause() {
        $condition = new CDbCriteria();
        $condition->compare('state', 'paused');
        $condition->addCondition('date_add(checked, INTERVAL 30 minute) < now()');
        $condition->order = ' checked asc';  
        
        $sites = Site::model()->findAll($condition);
        foreach ($sites as $site) {
            Yii::app()->l->log('Forced UNPAUSE of site ' . $site->id);
            $site->rehash(true);
        }
    }
    
    private function render($template, array $data = array()){
        $path = Yii::getPathOfAlias('application.views.email').'/'.$template.'.php';
        if(!file_exists($path)) throw new Exception('Template '.$path.' does not exist.');
        return $this->renderFile($path, $data, true);
    }
    

}