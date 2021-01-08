<?php

namespace srag\Plugins\SrSelfDeclaration\Config;

use ilSrSelfDeclarationPlugin;
use srag\DIC\SrSelfDeclaration\DICTrait;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrSelfDeclaration\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var Config[]
     */
    protected $configs = [];
    /**
     * @var bool[]
     */
    protected $has_access = [];
    /**
     * @var bool[]
     */
    protected $is_enabled = [];


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @internal
     */
    public function dropTables()/* : void*/
    {
        self::dic()->database()->dropTable(Config::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int $obj_id
     *
     * @return Config
     */
    public function getConfig(int $obj_id) : Config
    {
        if ($this->configs[$obj_id] === null) {
            $this->configs[$obj_id] = Config::where(["obj_id" => $obj_id])->first();

            if ($this->configs[$obj_id] === null) {
                $this->configs[$obj_id] = $this->factory()->newInstance();

                $this->configs[$obj_id]->setObjId($obj_id);
            }
        }

        return $this->configs[$obj_id];
    }


    /**
     * @param int $obj_ref_id
     * @param int $usr_id
     *
     * @return bool
     */
    public function hasAccess(int $obj_ref_id, int $usr_id) : bool
    {
        $cache_key = $obj_ref_id . "_" . $usr_id;

        if ($this->has_access[$cache_key] === null) {
            $this->has_access[$cache_key] = (self::srSelfDeclaration()->objects()->supportsObjType($obj_ref_id) && self::srSelfDeclaration()->objects()->hasWriteAccess($obj_ref_id, $usr_id));
        }

        return $this->has_access[$cache_key];
    }


    /**
     * @internal
     */
    public function installTables()/* : void*/
    {
        Config::updateDB();
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function isEnabled(int $obj_ref_id) : bool
    {
        if ($this->is_enabled[$obj_ref_id] === null) {
            $this->is_enabled[$obj_ref_id] = $this->getConfig(self::dic()->objDataCache()->lookupObjId($obj_ref_id))->isEnabled();
        }

        return $this->is_enabled[$obj_ref_id];
    }


    /**
     * @param Config $config
     */
    public function storeConfig(Config $config)/* : void*/
    {
        $config->store();

        $this->configs[$config->getObjId()] = $config;
        $this->is_enabled = [];
    }
}