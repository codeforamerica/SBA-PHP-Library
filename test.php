<?php
	require "lib/api.class.php";

	$sba = new SBA_API();
	$sba->set_format('json');

	/************ THIS IS A WAY TO TEST ALL OF THE FUNCTIONS *****************/
	
	/*
	echo $sba->licenses_permits_category('doing business as');
	echo $sba->licenses_permits_all_by_state('sd');
	echo $sba->licenses_permits_by_business_type('Plumber');
	echo $sba->licenses_permits_by_business_type_state_county('ca','los angeles county','child care services');
	echo $sba->licenses_permits_by_business_type_state_city('ca','los angeles','child care services');
	echo $sba->licenses_permits_by_business_type_zipcode('49684','restaurant');
	echo $sba->licenses_permits_by_business_type_state('ca','restaurant');

	echo $sba->city_county_data_urls('ca',TRUE,TRUE);	
	echo $sba->city_county_data_urls('ca',FALSE,TRUE);	
	echo $sba->city_county_data_urls('ca',TRUE,FALSE);	
	echo $sba->city_county_data_urls('ca',FALSE,'Los Angeles');	
	echo $sba->city_county_data_urls('ca','Los Angeles County',FALSE);	

	echo $sba->city_county_data_primary_urls('ca',TRUE,TRUE);	
	echo $sba->city_county_data_primary_urls('ca',FALSE,TRUE);	
	echo $sba->city_county_data_primary_urls('ca',TRUE,FALSE);	
	echo $sba->city_county_data_primary_urls('ca',FALSE,'Los Angeles');	
	echo $sba->city_county_data_primary_urls('ca','Los Angeles County',FALSE);	

	echo $sba->city_county_data_primary_urls('ca',TRUE,TRUE);	
	echo $sba->city_county_data_primary_urls('ca',FALSE,TRUE);	
	echo $sba->city_county_data_primary_urls('ca',TRUE,FALSE);	
	echo $sba->city_county_data_primary_urls('ca',FALSE,'Los Angeles');	
	echo $sba->city_county_data_primary_urls('ca','Los Angeles County',FALSE);	

	echo $sba->recommended_sites_all();	
	echo $sba->recommended_sites_by_keyword('contracting');	
	echo $sba->recommended_sites_by_category('managing a business');	
	echo $sba->recommended_sites_by_master_term('export');	
	echo $sba->recommended_sites_by_domain('irs');	

	echo $sba->loans_and_grants_federal();	
	echo $sba->loans_and_grants_state('ia');	
	echo $sba->loans_and_grants_federal_and_state('ia');	
	echo $sba->loans_and_grants_state_industry_specialty(FALSE, 'technology', FALSE);
	echo $sba->loans_and_grants_state_industry_specialty(FALSE, FALSE, array('exporting'));
	echo $sba->loans_and_grants_state_industry_specialty('ca', 'technology', FALSE);
	echo $sba->loans_and_grants_state_industry_specialty('ca', FALSE, array('exporting'));
	echo $sba->loans_and_grants_state_industry_specialty('ca', 'technology', array('exporting'));
	*/
?>