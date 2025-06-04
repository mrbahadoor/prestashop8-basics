<?php

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\Entity\Repository\TabRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Multiproductadd extends Module
{
    public function __construct()
    {
        $this->name = 'multiproductadd';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Rushdi Bahadoor';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Multi Product Add');
        $this->description = $this->l('Allows adding multiple products to the cart at once.');  

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');       

    }

    public function install(): bool
    {
        return parent::install()
            && $this->installTab()
            ;
    }

    public function uninstall(): bool
    {
        return parent::uninstall()
            && $this->uninstallTab()
            ;
    }

    private function installTab(): bool
    {
        $container = SymfonyContainer::getInstance();

        /** @var TabRepository */
        $tabRepository = $container->get('prestashop.core.admin.tab.repository');

        // Main block for sidebar menu
        $parentTabId = (int) $tabRepository->findOneIdByClassName('AdminRushdi');
        $parentTab = new Tab($parentTabId);
        
        $parentTab->class_name = 'AdminRushdi';
        $parentTab->name = array_fill_keys(Language::getIDs(true), 'Rushdi');
        $parentTab->module = $this->name;
        $parentTab->id_parent = 0;
        $parentTab->active = true;

        if (!(bool) $parentTabId) {
            $parentTab->add();
        } else {
            $parentTab->save();
        }
        // Ensure the parent tab is at the top of the menu
        $parentTab->updatePosition(true, 1);

        //Menu link for the module
        $tabId = (int) $tabRepository->findOneIdByClassName('AdminMultiproductadd');
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->class_name = 'AdminMultiproductadd';
        $tab->name = array_fill_keys(Language::getIDs(true), 'Multi Product Add');
        $tab->module = $this->name;
        $tab->id_parent = $parentTab->id;
        $tab->active = true; 
        $tab->icon = 'settings';

        return $tab->save();
    }


    private function uninstallTab(): bool
    {
        $container = SymfonyContainer::getInstance();

        /** @var TabRepository */
        $tabRepository = $container->get('prestashop.core.admin.tab.repository');

        // Get the tab ID for the module
        $parentTabId = (int) $tabRepository->findOneIdByClassName('AdminRushdi');
        $tabId = (int) $tabRepository->findOneIdByClassName('AdminMultiproductadd');
        
        if (!$parentTabId && !$tabId) {
            return true; // Tab does not exist, nothing to uninstall
        }

        // Delete the tab
        $tab = new Tab($tabId);        
        $deleted = $tab->delete();

        if (Tab::getNbTabs($parentTabId) == 0) {
            // If the parent tab has no children, delete it as well
            $parentTab = new Tab($parentTabId);
            return $deleted && $parentTab->delete();
        }

        return $deleted;
    }
}