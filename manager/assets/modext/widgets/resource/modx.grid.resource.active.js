MODx.grid.ActiveResources = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		title: _('resources_active')
        ,id: 'modx-grid-resource-active'
        ,url: MODx.config.connectors_url+'system/activeresource.php'
		,fields: ['id','pagetitle','username','editedon']
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 50 }
            ,{ header: _('page_title') ,dataIndex: 'pagetitle' ,width: 240 }
            ,{ header: _('sysinfo_userid') ,dataIndex: 'username' ,width: 180 }
            ,{ header: _('datechanged') ,dataIndex: 'editedon' ,width: 140 }]
		,paging: true
	});
	MODx.grid.ActiveResources.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ActiveResources,MODx.grid.Grid);
Ext.reg('modx-grid-resource-active',MODx.grid.ActiveResources);