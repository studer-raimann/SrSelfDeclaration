<?php

namespace srag\Plugins\SrSelfDeclaration\Declaration\Table;

use ilObject;
use ilSrSelfDeclarationPlugin;
use ilUserDefinedFields;
use ilUserProfile;
use srag\DataTableUI\SrSelfDeclaration\Component\Column\Column;
use srag\DataTableUI\SrSelfDeclaration\Component\Data\Row\RowData;
use srag\DataTableUI\SrSelfDeclaration\Component\Format\Format;
use srag\DataTableUI\SrSelfDeclaration\Component\Table;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Column\Formatter\DefaultFormatter;
use srag\DataTableUI\SrSelfDeclaration\Implementation\Utils\AbstractTableBuilder;
use srag\Plugins\SrSelfDeclaration\Declaration\DeclarationsCtrl;
use srag\Plugins\SrSelfDeclaration\Utils\SrSelfDeclarationTrait;

/**
 * Class TableBuilder
 *
 * @package srag\Plugins\SrSelfDeclaration\Declaration\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TableBuilder extends AbstractTableBuilder
{

    use SrSelfDeclarationTrait;

    const PLUGIN_CLASS_NAME = ilSrSelfDeclarationPlugin::class;
    /**
     * @var ilObject
     */
    protected $obj;


    /**
     * @inheritDoc
     *
     * @param DeclarationsCtrl $parent
     * @param ilObject         $obj
     */
    public function __construct(DeclarationsCtrl $parent, ilObject $obj)
    {
        parent::__construct($parent);

        $this->obj = $obj;
    }


    /**
     * @inheritDoc
     */
    protected function buildTable() : Table
    {
        $columns = [];

        foreach ((new ilUserProfile())->getStandardFields() as $key => $field) {
            if ($field["required_fix_value"] || self::dic()->settings()->get("usr_settings_" . ($this->obj->getType() === "grp" ? "group" : "course") . "_export_" . $key)) {
                $columns[] = self::dataTableUI()
                    ->column()
                    ->column($key, self::dic()->language()->txt($key))
                    ->withSortable(false)
                    ->withSelectable(!$field["required_fix_value"])
                    ->withFormatter(self::dataTableUI()->column()->formatter()->chainGetter(["usr", $field["method"]]));
            }
        }

        foreach (ilUserDefinedFields::_getInstance()->{"get" . ($this->obj->getType() === "grp" ? "Group" : "Course") . "ExportableFields"}() as $field) {
            $columns[] = self::dataTableUI()->column()->column($field["field_id"], $field["field_name"])->withSortable(false)->withFormatter(self::dataTableUI()
                ->column()
                ->formatter()
                ->chainGetter(["usr", "userDefinedData", "f_" . $field["field_id"]]));
        }

        $columns[] = self::dataTableUI()->column()->column("text",
            self::plugin()->translate("text", DeclarationsCtrl::LANG_MODULE))->withSortable(false)->withSelectable(false)->withFormatter(new class() extends DefaultFormatter {

            /**
             * @inheritDoc
             */
            public function formatRowCell(Format $format, $value, Column $column, RowData $row, string $table_id) : string
            {
                return nl2br(implode("\n", array_map("htmlspecialchars", explode("\n", $value))), false);
            }
        });

        $table = self::dataTableUI()->table(ilSrSelfDeclarationPlugin::PLUGIN_ID . "_declarations",
            self::dic()->ctrl()->getLinkTarget($this->parent, DeclarationsCtrl::CMD_LIST_DECLARATIONS, "", false, false),
            self::plugin()->translate("declarations", DeclarationsCtrl::LANG_MODULE), $columns, new DataFetcher($this->obj))->withPlugin(self::plugin());

        return $table;
    }
}