<?php
/**
 * Deletes a template.
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
$modx->lexicon->load('template','tv');

if (!$modx->hasPermission('delete_template')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get template and related tables */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('template_err_ns'));
$template = $modx->getObject('modTemplate',$_POST['id']);
if ($template == null) return $modx->error->failure($modx->lexicon('template_err_not_found'));

/* check to make sure it doesn't have any resources using it */
$resources = $modx->getCollection('modResource',array(
	'deleted' => 0,
	'template' => $template->get('id'),
));
if (count($resources) > 0) {
	$ds = '';
	foreach ($resources as $resource) {
		$ds .= $resource->get('id').' - '.$resource->get('pagetitle')." <br />\n";
    }

	return $modx->error->failure($modx->lexicon('template_err_in_use').$ds);
}

/* make sure isn't default template */
if ($template->get('id') == $default_template) {
	return $modx->error->failure($modx->lexicon('template_err_default_template'));
}

/* invoke OnBeforeTempFormDelete event */
$modx->invokeEvent('OnBeforeTempFormDelete',array('id' => $template->get('id')));

/* remove template var maps */
$templateTVs = $template->getMany('modTemplateVarTemplate');
foreach ($templateTVs as $ttv) {
	if ($ttv->remove() == false) {
        $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('tvt_err_remove'));
    }
}

/* delete template */
if ($template->remove() == false) {
	return $modx->error->failure($modx->lexicon('template_err_delete'));
}

/* invoke OnTempFormDelete event */
$modx->invokeEvent('OnTempFormDelete',array('id' => $template->get('id')));

/* log manager action */
$modx->logManagerAction('template_delete','modTemplate',$template->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();