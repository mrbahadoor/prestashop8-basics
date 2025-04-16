<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShopBundle\Entity\Repository\TabRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends Module
{
    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Rushdi Bahadoor';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('My module', [], 'Modules.Mymodule.Admin');
        $this->description = $this->trans('Description of my module.', [], 'Modules.Mymodule.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Mymodule.Admin');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->trans('No name provided', [], 'Modules.Mymodule.Admin');
        }
    }

    public function install()
    {
        // For multistore
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

    return (
            parent::install()
            && $this->installTab()
            && Configuration::updateValue('MYMODULE_NAME', 'my module')
        ); 
    }

    public function uninstall()
    {
        return (
            parent::uninstall()
            && $this->uninstallTab()
            && Configuration::deleteByName('MYMODULE_NAME')
        );
    }

    private function installTab(): bool
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminMyModule'; // Matches _legacy_controller
        $tab->route_name = 'mymodule_admin_configure'; // Your Symfony route name
        $tab->module = $this->name;
        // Deprecated
        // $tab->id_parent = (int) Tab::getIdFromClassName('IMPROVE'); // Or 'DEFAULT', etc. 
        
        // Access tab repository as a service
        // /** @var TabRepository */
        // $tabRepository = SymfonyContainer::getInstance()->get('prestashop.core.admin.tab.repository');
        
        // Get the parent tab ID
        // $parentTab = $tabRepository->findOneByClassName('IMPROVE');
        // if ($parentTab) {
        //     $tab->id_parent = (int) $parentTab->getId();
        // } else {
        //     // Handle the case where the parent tab is not found
        //     return false;
        // }
        $tab->id_parent = (int) Tab::getIdFromClassName('DEFAULT'); // Or 'DEFAULT', etc.

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'My Module';
        }

        return $tab->add();
    }

    private function uninstallTab(): bool
    {
        $idTab = Tab::getIdFromClassName('AdminMyModule');
        if ($idTab) {
            $tab = new Tab($idTab);
            return $tab->delete();
        }
        return true;
    }

}