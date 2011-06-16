<?php

require 'connection.class.php';
class SBA_API {

	/****** VARIABLES *****/
	private $_format, $_connection;
	
	//Used by business licenses and permits
	private $_business_categories 	= array('doing business as', 'entity filing', 'employer requirements', 'state licenses', 'tax registration');
	private $_business_type 			= array('general business licenses', 'auto dealership', 'barber shop', 'beauty salon', 'child care services', 'construction contractor', 'debt collection agency', 'electrician', 'massage', 'therapist', 'plumber', 'restaurant', 'insurance requirements', 'new hire reporting requirements', 'state tax registration', 'workplace poster requirements');

	//Used by loans and grants search api
	private $_loans_industry 	= array('agriculture', 'child care', 'environmental management', 'health care', 'manufacturing', 'technology', 'tourism'); 
	private $_loans_specialty	= array('general_purpose', 'development', 'exporting', 'contractor', 'green', 'military', 'minority', 'woman', 'disabled', 'rural', 'disaster');

	/****** BASIC CONSTRUCTION FUNCTIONS *****/
	public function __construct($format = 'xml') {
		$this->_format = $format;
		$this->_connection = ($connection) ? $connection : new Connection('http://api.sba.gov/');
	}
	
	public function get_format() {
		return $this->_format;
	}
	
	public function set_format($f) {
		if ($f == 'xml' || $f == 'json') {
			$this->_format = $f;
			return 'Format has been changed to '.$f.' successfully.';
		} else {
			return 'The format you provided is not valid. The only formats supported at this time are "xml" or "json".';
		}
	}
	
	public function test() {
		return $this->_connection->get('license_permit/state_and_city/restaurant/ny/albany.json', $data);
	}
	
	/******************* BUSINESS LICENSES & PERMITS API - http://www.sba.gov/about-sba-services/7615 *******************/

	/* Description:	Returns results for a matching license or permit category for each 54 states and territories. 
	 *						A category corresponds to a requirement that generally applies to all businesses.
	 * Note:				Uses $_business_categories
	 * Format:  		http://api.sba.gov/license_permit/by_category/CATEGORY.FORMAT
	 * Example: 		http://api.sba.gov/license_permit/by_category/tax registration.xml
	 * URL:				http://www.sba.gov/content/business-licenses-permits-api-category-method
	 */
	public function licenses_permits_category($c) {
		if (in_array($c,$this->_business_categories)) {
			return $this->_connection->get('license_permit/by_category/'.preg_replace('/ /','%20',$c).'.'.$this->_format, $data);
		} else {
			return "You included an invalid category. Please use one of the following: ".implode(', ',$this->_business_categories);
		}
	}

	/* Description:	Returns all business licenses for all business types required to operate in an specific state or territory.
	 * Format:  		http://api.sba.gov/license_permit/all_by_state/STATE ABBREVIATION.FORMAT
	 * Example: 		http://api.sba.gov/license_permit/all_by_state/ca.xml
	 * URL:				http://www.sba.gov/content/business-licenses-permits-api-state-method
	 */
	public function licenses_permits_all_by_state($c) {
		return $this->_connection->get('license_permit/all_by_state/'.$c.'.'.$this->_format, $data);
	}	
	
	/* Description:	Returns business licenses and permits required for a specific type of business for all 54 states and territories
	 * Note:				Uses the Business Types and the Employer Requirements
	 * Format:  		http://api.sba.gov/license_permit/by_business_type/BUSINESS TYPE.FORMAT
	 * Example: 		http://api.sba.gov/license_permit/by_business_type/child care services.xml
	 * URL:		
	 */
	public function licenses_permits_by_business_type($c) {
		if (in_array($c,$this->_business_type)) {
			return $this->_connection->get('license_permit/by_business_type/'.$c.'.'.$this->_format, $data);
		} else {
			return "You tried an invalid business type. Please use one of the following: ".implode(', ',$this->_business_type);
		}
	}
	
	/* Description:	Returns business licenses and permits required for a specific type of business in a specific state and county.
	 * Note:				Uses the Business Types and Employer Requirements
	 * Format:  		http://api.sba.gov/license_permit/state_and_county/BUSINESS TYPE/STATE ABBREVIATION/COUNTY NAME.FORMAT
	 * Example: 		http://api.sba.gov/license_permit/state_and_county/child care services/ca/los angeles county.xml
	 * URL:				http://www.sba.gov/content/business-licenses-permits-api-business-type-state-and-county-method
	 */
	public function licenses_permits_by_business_type_state_county($state,$county,$type) {
		if (in_array(strtolower($type),$this->_business_type)) {
			return $this->_connection->get('license_permit/state_and_county/'.$type.'/'.$state.'/'.$county.'.'.$this->_format, $data);
		} else {
			return "You tried an invalid business type. Please use one of the following: ".implode(', ',$this->_business_type);
		}
	}
	
	/* Description:	Returns business licenses and permits required for a specific type of business in a specific state and city.
	 * Note:				Uses the Business Types and Employer Requirements
	 * Format:  		http://api.sba.gov/license_permit/state_and_city/BUSINESS TYPE/STATE ABBREVIATION/CITY.FORMAT
	 * Example: 		http://api.sba.gov/license_permit/state_and_city/restaurant/ny/albany.xml
	 * URL:				http://www.sba.gov/content/business-licenses-permits-api-business-type-state-and-city-method
	 */
	public function licenses_permits_by_business_type_state_city($state,$city,$type) {
		if (in_array(strtolower($type),$this->_business_type)) {
			return $this->_connection->get('license_permit/state_and_city/'.$type.'/'.$state.'/'.$city.'.'.$this->_format, $data);
		} else {
			return "You tried an invalid business type. Please use one of the following: ".implode(', ',$this->_business_type);
		}
	}
	
	/* Description:	Returns business licenses and permits required for a specific type of business in a specific zipcode.
	 * Note:				Uses the Business Types and Employer Requirements
	 * Format:  		http://api.sba.gov/license_permit/by_zip/BUSINESS TYPE/ZIP CODE.xml
	 * Example: 		http://api.sba.gov/license_permit/by_zip/restaurant/49684.xml
	 * URL:				http://www.sba.gov/content/business-licenses-permits-api-business-type-and-zipcode-method
	 */
	public function licenses_permits_by_business_type_zipcode($zipcode,$type) {
		if (in_array(strtolower($type),$this->_business_type)) {
			return $this->_connection->get('license_permit/by_zip/'.$type.'/'.$zipcode.'.'.$this->_format, $data);
		} else {
			return "You tried an invalid business type. Please use one of the following: ".implode(', ',$this->_business_type);
		}
	}
	
	/* Description:	Returns business licenses and permits required for a specific type of business in a specific state.
	 * Note:				Uses the Business Types and Employer Requirements
	 * Format:  		http://api.sba.gov/license_permit/state_only/BUSINESS TYPE/STATE ABBREVIATION.FORMAT
	 * Example: 		http://api.sba.gov/license_permit/state_only/child care services/va.xml
	 * URL:			http://www.sba.gov/content/business-licenses-permits-api-business-type-and-state-method
	 */
	public function licenses_permits_by_business_type_state($state,$type) {
		if (in_array(strtolower($type),$this->_business_type)) {
			return $this->_connection->get('license_permit/state_only/'.$type.'/'.$state.'.'.$this->_format, $data);
		} else {
			return "You tried an invalid business type. Please use one of the following: ".implode(', ',$this->_business_type);
		}
	}

	/******************* US CITY AND COUNTY WEB DATA API - http://www.sba.gov/about-sba-services/7617 *******************/
		
	/* Description:	This geographic names data set provides a "mashup" of URLs for official city and county government web sites and city and county location data
	 * Note:				Is a mashup of methods. $county and $city can be a specific location, or can be set to TRUE for all, otherwise it's FALSE
	 * Format:  		http://api.sba.gov/geodata/city_county_links_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/city_links_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/county_links_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/all_links_for_city_of/CITY NAME/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/all_links_for_county_of/COUNTY NAME/STATE ABBREVIATION.xml
	 * Example: 		http://api.sba.gov/geodata/city_county_links_for_state_of/tx.xml
	 * URL:				http://www.sba.gov/about-sba-services/7617
	 */
	function city_county_data_urls($state, $county = FALSE, $city = FALSE) { 
		if ($county == TRUE && $city == TRUE) {
			return $this->_connection->get('geodata/city_county_links_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == FALSE && $city == TRUE) {
			return $this->_connection->get('geodata/city_links_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == TRUE && $city == FALSE) {
			return $this->_connection->get('geodata/county_links_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == FALSE && $city != FALSE && $city != TRUE) {
			return $this->_connection->get('geodata/all_links_for_city_of/'.$city.'/'.$state.'.'.$this->_format, $data);
		} else if ($city == FALSE && $county != FALSE && $county != TRUE) {
			return $this->_connection->get('geodata/all_links_for_county_of/'.$county.'/'.$state.'.'.$this->_format, $data);
		} else {
			return "Your parameters did not return any results. Please choose another combination of state and county or state and city.";
		}
	}
	
	/* Description:	This geographic names data set provides a "mashup" of URLs for official city and county government web sites and city and county location data
	 * Note:				Is a mashup of methods. $county and $city can be a specific location, or can be set to TRUE for all, otherwise it's FALSE
	 * Format:  		http://api.sba.gov/geodata/primary_city_county_links_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/primary_city_links_for_state_of/STATE ABBREVIATION.xml
							http://api.sba.gov/geodata/primary_county_links_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/primary_links_for_city_of/CITY NAME/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/primary_links_for_county_of/COUNTY NAME/STATE ABBREVIATION.xml
	 * Example: 		http://api.sba.gov/geodata/primary_city_county_links_for_state_of/tx.xml
	 * URL:				http://www.sba.gov/content/us-city-and-county-web-data-api-city-county-data-primary-methods
	 */
	function city_county_data_primary_urls($state, $county = FALSE, $city = FALSE) {
		if ($county == TRUE && $city == TRUE) {
			return $this->_connection->get('geodata/primary_city_county_links_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == FALSE && $city == TRUE) {
			return $this->_connection->get('geodata/primary_city_links_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == TRUE && $city == FALSE) {
			return $this->_connection->get('geodata/primary_county_links_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == FALSE && $city != FALSE && $city != TRUE) {
			return $this->_connection->get('geodata/primary_links_for_city_of/'.$city.'/'.$state.'.'.$this->_format, $data);
		} else if ($city == FALSE && $county != FALSE && $county != TRUE) {
			return $this->_connection->get('geodata/primary_links_for_county_of/'.$county.'/'.$state.'.'.$this->_format, $data);
		} else {
			return "Your parameters did not return any results. Please choose another combination of state and county or state and city.";
		}
	}
	
	/* Description:	These methods returns data for all city and counties, including those that do not have URLs associated with them. 
	 * Note:				Is a mashup of methods. $county and $city can be a specific location, or can be set to TRUE for all, otherwise it's FALSE
	 * Format:  		http://api.sba.gov/geodata/city_county_data_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/city_data_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/county_data_for_state_of/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/all_data_for_city_of/centerville/CITY NAME/STATE ABBREVIATION.FORMAT
							http://api.sba.gov/geodata/all_data_for_county_of/COUNTY NAME/STATE ABBREVIATION.xml
	 * Example: 		http://api.sba.gov/geodata/city_county_data_for_state_of/ca.xml
	 * URL:				http://www.sba.gov/content/us-city-and-county-web-data-api-city-county-data-all-data-methods
	 */
	function city_county_data_all($state, $county = FALSE, $city = FALSE) {
		if ($county == TRUE && $city == TRUE) {
			return $this->_connection->get('geodata/city_county_data_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == FALSE && $city == TRUE) {
			return $this->_connection->get('geodata/city_data_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == TRUE && $city == FALSE) {
			return $this->_connection->get('geodata/county_data_for_state_of/'.$state.'.'.$this->_format, $data);
		} else if ($county == FALSE && $city != FALSE && $city != TRUE) {
			return $this->_connection->get('geodata/all_data_for_city_of/'.$city.'/'.$state.'.'.$this->_format, $data);
		} else if ($city == FALSE && $county != FALSE && $county != TRUE) {
			return $this->_connection->get('geodata/all_data_for_county_of/'.$county.'/'.$state.'.'.$this->_format, $data);
		} else {
			return "Your parameters did not return any results. Please choose another combination of state and county or state and city.";
		}
	}
	
	/******************* RECOMMENDED SITES API - http://www.sba.gov/about-sba-services/7630 *******************/
	
	/* Description:	Get all recommended sites with all details
	 * Note:	
	 * Format:  		http://api.sba.gov/rec_sites/all_sites/keywords.xml
	 * Example: 		http://api.sba.gov/rec_sites/all_sites/keywords.xml
	 * URL:				http://www.sba.gov/about-sba-services/7630
	 */
	public function recommended_sites_all() {
		return $this->_connection->get('rec_sites/all_sites/keywords.'.$this->_format, $data);
	}	
	
	/* Description:	Returns all recommended sites for a specific keyword.
	 * Note:	
	 * Format:  		http://api.sba.gov/rec_sites/keywords/[KEYWORD].xml
	 * Example: 		http://api.sba.gov/rec_sites/keywords/contracting.xml
	 * URL:				http://www.sba.gov/about-sba-services/7630
	 */
	public function recommended_sites_by_keyword($keyword) {
		return $this->_connection->get('rec_sites/keywords/'.$keyword.'.'.$this->_format, $data);
	}	
	
	/* Description:	Returns all recommended sites by category.
	 * Note:	
	 * Format:  		http://api.sba.gov/rec_sites/category/[CATEGORY].xml
	 * Example: 		http://api.sba.gov/rec_sites/category/managing a business.xml
	 * URL:				http://www.sba.gov/about-sba-services/7630
	 */
	public function recommended_sites_by_category($category) {
		return $this->_connection->get('rec_sites/category/'.$category.'.'.$this->_format, $data);
	}	
	
	/* Description:	Returns all sites by a master term
	 * Note:	
	 * Format:  		http://api.sba.gov/rec_sites/keywords/master_term/[MASTER TERM].xml
	 * Example: 		http://api.sba.gov/rec_sites/keywords/master_term/export.xml
	 * URL:				http://www.sba.gov/about-sba-services/7630
	 */
	public function recommended_sites_by_master_term($master) {
		return $this->_connection->get('rec_sites/keywords/master_term/'.$master.'.'.$this->_format, $data);
	}	
	
	/* Description:	Returns all recommended sites belonging to a specific domain (e.g., sba.gov).
	 * Note:	
	 * Format:  		http://api.sba.gov/rec_sites/keywords/domain/[DOMAIN].xml
	 * Example: 		http://api.sba.gov/rec_sites/keywords/domain/irs.xml
	 * URL:				http://www.sba.gov/about-sba-services/7630
	 */
	public function recommended_sites_by_domain($domain) {
		return $this->_connection->get('rec_sites/keywords/domain/'.$domain.'.'.$this->_format, $data);
	}	
	
	
	/******************* LOANS AND GRANTS SEARCH API - http://www.sba.gov/about-sba-services/7616 *******************/
	
	
	/* Description:	Returns all small business financing programs sponsored by Federal government agencies, including SBA and USDA; and private non-profit organizations.
	 * Note:	
	 * Format:  		http://api.sba.gov/loans_grants/federal.FORMAT
	 * Example: 		http://api.sba.gov/loans_grants/federal.xml
	 * URL:				http://www.sba.gov/content/loans-grants-search-api-federal-program-method
	 */
	public function loans_and_grants_federal() {
		return $this->_connection->get('loans_grants/federal.'.$this->_format, $data);
	}	
	
	/* Description:	Returns all small business financing programs sponsored by state government agencies and select non-profit and commercial organizations.
	 * Note:	
	 * Format:  		http://api.sba.gov/loans_grants/state_financing_for/STATE ABBREVIATION.FORMAT
	 * Example: 		http://api.sba.gov/loans_grants/state_financing_for/ia.xml
	 * URL:				http://www.sba.gov/content/loans-grants-search-api-programs-specific-state-method
	 */
	public function loans_and_grants_state($state) {
		return $this->_connection->get('loans_grants/state_financing_for/'.$state.'.'.$this->_format, $data);
	}	
	
	/* Description:	Returns all small business financing programs sponsored by federal and state government agencies and selected non-profit and commercial organizations.
	 * Note:	
	 * Format:  		http://api.sba.gov/loans_grants/federal_and_state_financing_for/STATE ABBREVIATION.FORMAT
	 * Example: 		http://api.sba.gov/loans_grants/federal_and_state_financing_for/me.xml
	 * URL:				http://www.sba.gov/content/loans-grants-search-api-federal-and-state-specific-method
	 */
	public function loans_and_grants_federal_and_state($state) {
		return $this->_connection->get('loans_grants/federal_and_state_financing_for/'.$state.'.'.$this->_format, $data);
	}	
	
	function loans_and_grants_search($state = FALSE, $industry = FALSE, $specialty = FALSE) { } 
	/* Description:	Returns small business special financing programs by state, industry, and|or specialty 
	 * Note:				State Zipcode, an Industry, and an array of Specialties. not specifying will let them return false
	 * Format:  		http://api.sba.gov/loans_grants/nil/for_profit/INDUSTRY/nil.xml
							http://api.sba.gov/loans_grants/nil/for_profit/nil/SPECIALTY1-SPECIALTY2-SPECIALTYN.FORMAT
							http://api.sba.gov/loans_grants/nil/for_profit/INDUSTRY/SPECIALTY.FORMAT
							http://api.sba.gov/loans_grants/STATE ABBREVIATION/for_profit/INDUSTRY/nil.FORMAT
							http://api.sba.gov/loans_grants/STATE ABBREVIATION/for_profit/nil/SPECIALTY1-SPECIALTY2-SPECIALTYN.FORMAT
							http://api.sba.gov/loans_grants/STATE ABBREVIATION/for_profit/INDUSTRY/SPECIALTY1-SPECIALTY2-SPECIALTYN.FORMAT
	 * Example: 		http://api.sba.gov/loans_grants/nil/for_profit/manufacturing/nil.xml
	 * URL:				http://www.sba.gov/about-sba-services/7616
	 */
	public function loans_and_grants_state_industry_specialty($state, $industry, $specialty) {
		//error check first, and return error result
		if ($industry != FALSE && !in_array($industry,$this->_loans_industry)) {
			return "early failure results";
		} else if ($specialty != FALSE && !is_array($specialty)) {
			if (!in_array($specialty,$this->_loans_specialty)) {
				return "You tried an invalid specialty. Please use any combination of the following: ".implode(', ',$this->_loans_specialty);
			} else {
				$specialty = array($specialty);
			}
		} else if ($specialty != FALSE && is_array($specialty)) {
			foreach ($specialty as $k) {
				if (!in_array($k,$this->_loans_specialty)) {
					return "You tried an invalid specialty. Please use any combination of the following: ".implode(', ',$this->_loans_specialty);
				}
			}
		}
		
		//if it doesn't have invalid data input, then progress to show the view
		if ($state == FALSE && in_array($industry,$this->_loans_industry) && $specialty == FALSE) {
			return $this->_connection->get('loans_grants/nil/for_profit/'.$industry.'/nil.'.$this->_format, $data);
		} else if ($state == FALSE && $industry == FALSE && is_array($specialty)) {
			return $this->_connection->get('loans_grants/nil/for_profit/nil/'.implode('-',$specialty).'.'.$this->_format, $data);
		} else if ($state == FALSE && in_array($industry,$this->_loans_industry) && is_array($specialty)) {
			return $this->_connection->get('loans_grants/'.$state.'/for_profit/nil/'.implode('-',$specialty).'.'.$this->_format, $data);
		} else if ($state != FALSE && in_array($industry,$this->_loans_industry) && $specialty == FALSE) {
			return $this->_connection->get('loans_grants/'.$state.'/for_profit/'.$industry.'/nil.'.$this->_format, $data);
		} else if ($state != FALSE && $industry == FALSE && is_array($specialty)) {
			return $this->_connection->get('loans_grants/'.$state.'/for_profit/nil/'.implode('-',$specialty).'.'.$this->_format, $data);
		} else if ($state != FALSE && in_array($industry,$this->_loans_industry) && is_array($specialty)) {
			return $this->_connection->get('loans_grants/nil/for_profit/'.$industry.'/'.implode('-',$specialty).'.'.$this->_format, $data);
		} else {
			return "There was an error with your request. Please try again.";
		}
	}	

}
?>