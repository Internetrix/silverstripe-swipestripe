<?php
class SubsitePayment_ProcessorExtension extends Extension {

	public function onBeforeRedirect() {

		//setup correct theme name for this domain.
		if($subsite = Subsite::currentSubsite()){
			if($theme = $subsite->Theme)
				SSViewer::set_theme($theme);
		}
		
	}
}