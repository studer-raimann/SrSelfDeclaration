<!-- Autogenerated from composer.json - All changes will be overridden if generated again! -->

# SrSelfDeclaration ILIAS Plugin

Declaration of object members

This is an OpenSource project by studer + raimann ag, CH-Burgdorf (https://studer-raimann.ch)

This project is licensed under the GPL-3.0-only license

## Requirements

* ILIAS 5.4.0 - 6.999
* PHP >=7.0

## Installation

Start at your ILIAS root directory

```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone https://github.com/studer-raimann/SrSelfDeclaration.git SrSelfDeclaration
```

Update, activate and config the plugin in the ILIAS Plugin Administration

## Description

Current only courses are supported

### Config

You can enable it per object and set a default declaration text

#### Tab

![Config tab](./doc/images/config_tab.png)

#### Form

![Config form](./doc/images/config_form.png)

### Declaration block

Object members, with read permission only, can set declaration once

#### Before fill

![Declaration block before fill](./doc/images/declaration_block_before_fill.png)

#### Form

![Declaration form](./doc/images/declaration_form.png)

#### After fill

![Declaration block after fill](./doc/images/declaration_block_after_fill.png)

### Declarations

The declarations of object can be viewed from object administrators

#### Tab

![Declarations tab](./doc/images/declarations_tab.png)

#### Table

![Declarations table](./doc/images/declarations_table.png)

## Adjustment suggestions

You can report bugs or suggestions at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLSELDEC

There is no guarantee this can be fixed or implemented

## ILIAS Plugin SLA

We love and live the philosophy of Open Source Software! Most of our developments, which we develop on behalf of customers or on our own account, are publicly available free of charge to all interested parties at https://github.com/studer-raimann.

Do you use one of our plugins professionally? Secure the timely availability of this plugin for the upcoming ILIAS versions via SLA. Please inform yourself under https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Please note that we only guarantee support and release maintenance for institutions that sign a SLA.
