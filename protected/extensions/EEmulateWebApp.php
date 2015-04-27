<?php


class EEmulateWebApp extends CBehavior {
    
    private $ready = false;
    
    private function prepare() {
        if ($this->ready) {
            return;
        }
        $components=array(
            'widgetFactory'=>array(
                'class'=>'CWidgetFactory',
            ),
            'assetManager' => array(
                'class'=>'CAssetManager',
            ),
            'clientScript' => array(
                'class'=>'CClientScript',
            ),
            'themeManager' => array(
                'class'=>'CThemeManager',
            ),
            'viewRenderer' => array(
                'class'=>'CPradoViewRenderer',
            ),
		);
        $owner = $this->getOwner();
		$owner->setComponents($components);
    } 
    
    public function getWidgetFactory()
	{
	    $this->prepare();
	    $owner = $this->getOwner();
		return $owner->getComponent('widgetFactory');
	}

	public function getAssetManager()
	{
	    $this->prepare();
	    $owner = $this->getOwner();
	    $assetManager = $owner->getComponent('assetManager');
		$assetManager->setBasePath(realpath(dirname(__FILE__) . '/../../assets/'));
		return $assetManager;
    }
	
    public function getClientScript()
	{
	    $this->prepare();
	    $owner = $this->getOwner();
		return $owner->getComponent('clientScript');
	}
	
	public function getThemeManager()
	{
	    $this->prepare();
	    $owner = $this->getOwner();
		$themeManager = $owner->getComponent('themeManager');
		$themeManager->setBasePath(realpath(dirname(__FILE__) . '/../../themes/'));
		return $themeManager;
	}

	/**
	 * @return CTheme the theme used currently. Null if no theme is being used.
	 */
	public function getTheme()
	{
		$this->prepare();
		return $this->getThemeManager()->getTheme('classic');
	}
	
	
	public function getViewRenderer()
	{
	    $owner = $this->getOwner();
		return $owner->getComponent('viewRenderer');
	}
	
	public function getViewPath() {
	    return realpath(dirname(__FILE__) . '/../../protected/views/');
	}
    
}