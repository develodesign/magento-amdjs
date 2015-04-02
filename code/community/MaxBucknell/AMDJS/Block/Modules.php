<?php

class MaxBucknell_AMDJS_Block_Modules extends Mage_Core_Block_Template
{
    /**
     * @var array The modules that are being used in this request
     */
    protected $_modules = array();

    protected function _construct ()
    {
        parent::_construct();
        $this->setTemplate('maxbucknell/amdjs/modules.phtml');
    }

    /**
     * Add a js file.
     * If an index is defined then add it at the index.
     *
     * @param $url string
     * @param null $position
     */
    private function _add( $url, $position = null )
    {
        if( ! $position )

            $this->_modules[] = $url;

        else

            $this->_modules[ $position ] = $url;
    }

    /**
     *
     */
    protected function _beforeToHtml()
    {
        $helper = Mage::helper('MaxBucknell_AMDJS');

        if (!$helper->isModuleSetCached($this->_modules)) {
            $helper->cacheModuleSet($this->_modules);
        }

        $this->assign('requireJSUrl', $this->getJsUrl('maxbucknell/amdjs/lib/bower_components/requirejs/require.js'));
        $this->assign('compiledScriptURL', $helper->getBuiltFileUrl($this->_modules));
        $this->assign('modules', array_values($this->_modules));
    }

    /**
     * Adds a javascript file and an optional position.
     * If position is taken it'll save the item at the old position, add the new item, then save the original
     * item add the end of the array.
     *
     * @param $url String
     * @param $position String|Integer - index
     */
    public function addModule( $url, $position = null )
    {
        if( ! $position || ! $this->_isPositionTaken( $position ) )

            $this->_add( $url, $position );

        else
        {
            // Position is probably taken.

            $originalItem = $this->_modules[ $position ];

            $this->_add( $url, $position );

            $this->_add( $originalItem );
        }
    }

    /**
     * Add a js file from the skin directory of the current theme
     *
     * @param $file
     * @param $position
     */
    public function addSkinJs( $file, $position = null )
    {
        $this->addModule( $this->getSkinUrl( $file ), $position );
    }

    /**
     * Stop using a module on this page.
     *
     * @param string $moduleName
     * @return void
     */
    public function removeModule($moduleName)
    {
        unset($this->_modules[$moduleName]);
    }
}
