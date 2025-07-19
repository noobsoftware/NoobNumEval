<?

class evaluation {
	

	public $cached_all = true;

	public $trigonometry;
	
	public $binary_modulus;
	
	public $division;	

	public function __construct() {
		$this->trigonometry = new trigonometry($this);
		/*$this->division = new division($this);*/
		$this->binary_modulus = new binary_modulus($this, '0');
	}
	
	public function __set_configuration($truncate_fractions_length=0, $logarithm_iteration_count=12, $root_fraction_precision=['value' => '0', 'remainder' => '1/100'], $disable_built_in_approximation=false, $sine_precision=10, $set_continued_fraction_resolution_level_setting=12, $disable_exact_root_results=false) {
		if($truncate_fractions_length != NULL) {
			$this->set_truncate_fractions($truncate_fractions_length);
		}
		if($logarithm_iteration_count != NULL) {
			$this->logarithm_iteration_count = $logarithm_iteration_count;	
		}
		if($root_fraction_precision != NULL) {
			$this->root_fraction_precision = $root_fraction_precision;	
		}
		if($disable_built_in_approximation != NULL) {
			$this->disable_built_in_approximation = $disable_built_in_approximation;	
		}
		if($sine_precision != NULL) {
			$this->trigonometry->sine_precision = $sine_precision;	
		}
		if($set_continued_fraction_resolution_level_setting != NULL) {
			$this->set_continued_fraction_resolution_level_setting = $set_continued_fraction_resolution_level_setting;	
		}
		if($disable_exact_root_results != NULL) {
			$this->disable_exact_root_results = $disable_exact_root_results;	
		}
	}
	
	
	public function get_digits($term, $remove_decimal_point=true, $remove_negative=true) {
		if($remove_decimal_point) {
			$term = $object->strings->explode('.', $term);
			$term = $object->strings->implode('', $term);
		}
		
		$digits = $object->strings->str_split($term);
		$digits = $object->reverse($digits);
		return $digits;	
		/*return $math->get_digits($term, $remove_decimal_points, $remove_negative);*/
	}
	
	private $start_exponent;
	private $exponent;
	private $intermediate_results;
	

	public function result($term_a, $term_b) {
		return $math->result($term_a, $term_b);
		$negative = false;
		if(($this->negative($term_a) && !$this->negative($term_b)) || ($this->negative($term_b) && !$this->negative($term_a))) {
			$negative = true;
		}
		$result = $this->result_sub($this->absolute($term_a), $this->absolute($term_b));
		if($negative) {
			$result = '-'.$result;	
		}
		return $result;
	}

	public function add($term_a, $term_b) {
		return $math->add($term_a, $term_b);
		/*if(!$object->isset($term_a)) {
			$term_a = 0;
		}
		if(!$object->isset($term_b)) {
			$term_b = 0;
		}*/
		if($this->negative($term_a) && $this->negative($term_b)) {
			return '-'.$this->add_sub($this->absolute($term_a), $this->absolute($term_b));	
		} else if($this->negative($term_a) && !$this->negative($term_b)) {
			return $this->subtract($this->absolute($term_b), $this->absolute($term_a));
		} else if(!$this->negative($term_a) && $this->negative($term_b)) {
			return $this->subtract($this->absolute($term_a), $this->absolute($term_b));
		} else {
			return $this->add_sub($this->absolute($term_a), $this->absolute($term_b));	
		}
	}
	
	public function add_multiple($values) {
		$result = '0';
		foreach($values as $value) {
			$result = $this->add($result, $value);	
		}
		return $result;
	}


	public function subtract_sub($term_a, $term_b, $base=10, $limit_decimals=false) {
		$decimal_point = -1;
		if($object->strings->strpos($term_a, '.') != (-1) || $object->strings->strpos($term_b, '.') != (-1)) {
			$terms = $this->synchronize_values($term_a, $term_b);
			$term_a = $terms['a'];
			$term_b = $terms['b'];
			$decimal_point = $terms['fraction_length'];
		}
		
		$a_digits = $this->get_digits($term_a);
		$b_digits = $this->get_digits($term_b);
		$minus_sign = '';
		if($this->larger($term_b, $term_a) && $term_b != $term_a) { 			
			$switch = $a_digits;
			$a_digits = $b_digits;
			$b_digits = $switch;	
			$minus_sign = '-';
		}
		
		
		$return_digits = [];
		$carry_value = NULL;
		$carry_index = [];
		foreach($a_digits as $key_a => $a_digit) {
			$addition;
			/*if($object->isset($b_digits[$key_a])) {*/
			if($b_digits->length > $key_a) {
				if($a_digit == '') {
					$a_digit = 0;	
				}
				$b_digit = $b_digits[$key_a];
				if($b_digit == '') {
					$b_digit = 0;	
				}
				$addition = $a_digit - $b_digit;
			} else {			
				$addition = $a_digit;	
			}
			if($carry_value != NULL) {
				$addition -= $carry_value;
				$carry_value = NULL;	
			}
			if((($addition < 0 && $key_a > 0) || ($base == 10 && $addition < 0))) {
				$carry_value = 1;
				$addition = $this->remove_minus($addition);
				$addition = 10-$addition;
			} 
			
			$return_digits[] = $addition;
		}
		
		
		$return_digits = $object->reverse($return_digits);
		$result = $object->strings->implode('', $return_digits);
		$result = $this->remove_leading_zeros($result);
		if($decimal_point != (-1)) {
			$result = $this->place_decimal($result, $decimal_point, true, true);	
		}
		$result = $minus_sign.$result;
		if($result == '') {
			$result = 0;	
		}
		$result = $this->remove_leading_zeros($result);
		return $result;
	}

	private function remove_minus($value) {
		if($object->strings->strpos($value, '-') != (-1)) {
			$split = $object->strings->explode('-', $value);
			$value = $split[1];	
		}
		return $value;
	}

	private function result_sub($term_a, $term_b) {
		$intermediate_results = [];
		if($term_a == 0 || $term_b == 0) {
			return 0;	
		}
		
		$a_digits = $this->get_digits($term_a);
		$b_digits = $this->get_digits($term_b);
		$index = 0;
		$start_stop = false;
		foreach($b_digits as $exponent_b => $value_b) {
			if($value_b != 0) {
				foreach($a_digits as $exponent_a => $value_a) {
					if($value_a != 0) {
						$value = $math->mult($value_a, $value_b);
						$exponent = $exponent_a+$exponent_b;
						$intermediate_results[] = [
							'value' => $value,
							'exponent' => $exponent
						];
					}
				}
			}
		}
		$result = NULL;
		foreach($intermediate_results as $result_value) {
			$result_value = $this->numeric_value($result_value);
			if($result == NULL) {
				$result = $result_value;
			} else {
				$result = $this->add($result, $result_value);	
			}
		}
		return $result;
	}
	


	public function subtract($term_a, $term_b) {
		return $math->subtract($term_a, $term_b);
		if($object->strings->strpos($term_a, '-') != (-1) && $object->strings->strpos($term_b, '-') != (-1)) {
			$term_a = $object->strings->explode('-', $term_a)[1];
			$term_b = $object->strings->explode('-', $term_b)[1];
			return $this->negative_value($this->subtract_sub($term_b, $term_a));
		} else if($object->strings->strpos($term_a, '-') == (-1) && $object->strings->strpos($term_b, '-') != (-1)) {
			$term_b = $object->strings->explode('-', $term_b)[1];
			return $this->add($term_a, $term_b);
		} else if($object->strings->strpos($term_a, '-') != (-1) && $object->strings->strpos($term_b, '-') == (-1)) {
			$term_a = $object->strings->explode('-', $term_a)[1];
			return '-'.$this->add($term_a, $term_b);
		} else {
			return $this->subtract_sub($term_a, $term_b);	
		}
	}
	
	public function lengthen_fraction($value, $length) {
		$fraction = $object->strings->explode('/', $value);
		$numerator = $this->result($length, $fraction[0]);		
		$denominator = $this->result($length, $fraction[1]);
		return $numerator.'/'.$denominator;	
	}
	
	public function lengthen_to($value, $length_to) {
		$fraction = $this->fraction_values($value);
		$denominator = $fraction[1];
		$fraction_translation = $this->execute_divide($length_to, $denominator);		
		$fraction_translation = $fraction_translation['value'];
		return $this->lengthen_fraction($this->fraction_string($fraction), $fraction_translation);	
	}
	
	private $unit_fraction_limit = 100;
	
	public function unit_fraction($value) {
		$values = $this->fraction_values($value);
		if($values[0] < 1) {
			$fraction_translation = 1 / $values[0];
			$numerator = 1;
			$denominator = $math->mult($values[1], $fraction_translation);
			if(!$this->fraction($denominator)) {
				$counter = 0;
				$denominator_change = $denominator;
				while(!$this->fraction($denominator_change) && $counter < $this->unit_fraction_limit) {
					$counter++;
					$denominator_change = $math->mult($denominator, $counter);
				}
				$numerator = $counter;
				$denominator = $denominator_change;
			}
			return $numerator.'/'.$denominator;
		}
		return $value;
	}
	
	public function common($value, $shorten=false) {
		$decimal_point = $object->strings->strpos($value, '.');
		$assembly = $value;
		if($decimal_point != (-1)) {
			$length = $object->strings->strlen($value);
			$split = $object->strings->explode('.', $value);
			$assembly = $this->remove_leading_zeros($split[0].$split[1]);
			$denominator_decimals = $length - $decimal_point;
			$denominator = $this->make_decimal_value($denominator_decimals);
			$assembly = $assembly.'/'.$denominator;
		}
		if($shorten) {
			$assembly = $this->execute_shorten_fraction($assembly);
		}
		return $assembly;
	}
	
	public function remove_leading_zeros($value, $reverse=false) {
		$digits = $object->strings->str_split($value);
		if($reverse) {
			$digits = $this->get_digits($value, false);	
		}
		$counter = 0;
		$non_zero = false;
		
		$result = '';
		$zero_count = 0;
		foreach($digits as $counter => $digit) {
			if(!$non_zero) {
				if($digit != '0') {
					$non_zero = true;
					$result .= $digit;	
				} else {
					$zero_count++;	
				}
			} else {
				$result .= $digit;	
			}
		}
		
		if($reverse) {
			$result = $object->strings->strrev($result);	
		}
		if($object->strings->trim($result) == '') {
			$result = '0';	
		}
		return $result;
	}
	
	private function make_decimal_value($length) {
		$counter = 1;
		$return_value = '';
		while($counter < $length) {
			$return_value .= '0';
			$counter++;	
		}
		$return_value = '1'.$return_value;
		return $return_value;
	}
	
	public function fraction($value) {
		if($object->strings->strpos($value, '.') != (-1)) {
			return true;	
		}
		return false;
	}
	
	public function multiply_fraction($value_a, $value_b, $shorten=false) {
		if($value_a == '' || $value_b == '' || $value_a == NULL || $value_b == NULL) {
			return '0/1';	
		}
		
		$fraction_a = $this->fraction_values($value_a);
		$fraction_b;
		if($object->strings->strpos($value_b, '/') != (-1)) {
			$fraction_b = $this->fraction_values($value_b);
		} else {
			$fraction_b = [
				$value_b,
				$value_b
			];
		}
		if($fraction_a[0] == 0 || $fraction_b[0] == 0) {
			return '0/1';	
		}
		$numerator = $this->result($fraction_a[0], $fraction_b[0]);		
		$denominator = $this->result($fraction_a[1], $fraction_b[1]);		
		$result = $numerator.'/'.$denominator;
		return $result;
	}
	
	public function multiply_total($value_a, $value_b, $shorten=false) {
		return $math->multiply_total($value_a, $value_b);
		$negative_result = false;
		if($this->negative($value_a) && !$this->negative($value_b)) {
			$negative_result = true;	
		}
		if(!$this->negative($value_a) && $this->negative($value_b)) {
			$negative_result = true;	
		}
		$value_a = $this->absolute($value_a);
		$value_b = $this->absolute($value_b);
		$result = $this->multiply_total_sub($value_b, $value_a['value']);
		$multiplication = $this->multiply_total_sub(['value' => '0', 'remainder' => $value_a['remainder']], $value_b['value']);
		$result = $this->add_total($result, $multiplication);
		$result = $this->add_total($result, ['value' => '0', 'remainder' => $this->multiply_fraction($value_a['remainder'], $value_b['remainder'])]);
		if($shorten) {
			$result['remainder'] = $this->execute_shorten_fraction($result['remainder']);	
		}
		if($negative_result) {
			$result = $this->negative_value($result);	
		}
		$result = $this->clean_remainder($result);
		return $result;
	}
	
	/*private function multiply_total_sub($value_a, $value_b) {
		$result = $this->result($value_a['value'], $value_b);		
		$fraction = $this->multiply_fraction($value_a['remainder'], ($value_b.'/1'));		
		$fraction_values = $this->fraction_values($fraction);
		$division = ['value' => '0', 'remainder' => '0/1'];
		while($this->larger($fraction_values[0], $fraction_values[1])) {
			$division['value'] = $this->add($division['value'], 1);
			$fraction_values[0] = $this->subtract($fraction_values[0], $fraction_values[1]);
		}
		$division = $this->execute_divide($fraction_values[0], $fraction_values[1]);
		if($this->larger($division['value'], 0)) {
			$result = $this->add($result, $division['value']);
			$fraction = $division['remainder'];
		}
		return [
			'value' => $result,
			'remainder' => $fraction
		];	
	}*/

	public function multiply_total_sub($value_a, $value_b) {
		$result = $this->result($value_a['value'], $value_b);		
		$fraction = $this->multiply_fraction($value_a['remainder'], ($value_b.'/1'));		
		$fraction_values = $this->fraction_values($fraction);
		$division = $this->execute_divide($fraction_values[0], $fraction_values[1]);
		if($this->larger($division['value'], 0)) {
			$result = $this->add($result, $division['value']);
			$fraction = $division['remainder'];
		}
		return [
			'value' => $result,
			'remainder' => $fraction
		];	
	}

	/*public function multiply_total_sub($value_total, $value) {
		$base_value = $this->result($value_total['value'], $value);
		$fraction_values = $this->fraction_values($value_total['remainder']);
		$t = $this->result($fraction_values[0], $value);
		$t = $this->execute_divide($t, $fraction_values[1]);
		$t['value'] = $this->add($base_value, $t['value']);
		return $t;
	}*/
	
	public function minimize_fraction($value) {
		$fraction = $this->fraction_values($value);
		$numerator = $fraction[0];
		$denominator = $fraction[1];
		$numerator_digits = $this->get_digits($numerator);
		$denominator_digits = $this->get_digits($denominator);
		$numerator_non_zero_point = (-1);
		$denominator_non_zero_point = (-1);
		foreach($numerator_digits as $key => $value) {
			if($value != '0' && $numerator_non_zero_point == (-1)) {
				$numerator_non_zero_point = $key;	
			}
		}
		foreach($denominator_digits as $key => $value) {
			if($value != '0' && $denominator_non_zero_point == (-1)) {
				$denominator_non_zero_point = $key;	
			}
		}
		$cutoff;
		if($numerator_non_zero_point < $denominator_non_zero_point) {
			$cutoff = $numerator_non_zero_point;	
		} else {
			$cutoff = $denominator_non_zero_point;	
		}
		$numerator = '';
		$denominator = '';
		foreach($numerator_digits as $key => $value) {
			if($key >= $cutoff) {
				$numerator = $value.$numerator;
			}
		}
		foreach($denominator_digits as $key => $value) {
			if($key >= $cutoff) {
				$denominator = $value.$denominator;
			}
		}
		return $numerator.'/'.$denominator;
	}
		
	public function ceil($value) {
		if($this->negative($value)) {
			return $this->negative_value($this->floor($this->absolute($value)));	
		}
		if($this->fraction_values($value['remainder'])[0] != '0') {
			return $this->add($value['value'], 1);	
		}
		return $value['value'];
	}
	
	public function round($value) {
		if($this->fraction_values($value['remainder'])[0] == 0) {
			return $value['value'];
		}
		$fraction_values = $this->fraction_values($value['remainder']);
		$numerator = $fraction_values[0];
		$denominator = $fraction_values[1];
		
		$numerator = $this->result($numerator, 2);
		if($this->larger($numerator, $denominator)) {
			return $this->add($value['value'], 1);	
		}
		return $value['value'];
	}
	
	
	
	public $truncate_fractions = false;
	public $truncate_fractions_length = NULL;
	
	public function set_truncate_fractions($length) {
		if($length != false && $length != NULL && $length > 0) {
			$this->truncate_fractions = true;
			$this->truncate_fractions_length = $length;	
		} else {
			$this->truncate_fractions = false;	
		}
	}
	
	private function string_prefix($depth) {
		$counter = 0;
		$return_string = '-';
		while($counter <= $depth) {
			$return_string .= '-';
			$counter++;
		}
		return $return_string;
	}
	
	private $minimal_divider_set = 7;
	private $mapped_minimal_dividers;
	private $untouch_value = NULL;
		
	public function common_denominator($value_a, $value_b) {
		$fraction_values_a = $this->fraction_values($value_a);
		$fraction_values_b = $this->fraction_values($value_b);
		if($fraction_values_a[0] == 0) {
			return [
				'0/'.$fraction_values_b[1],
				$value_b
			];
		} else if($fraction_values_b[0] == 0) {
			return [
				$value_a,
				'0/'.$fraction_values_a[1]
			];
		}

		$denominator = $this->result($fraction_values_a[1], $fraction_values_b[1]);
		$result_a = $this->result($fraction_values_b[1], $fraction_values_a[0]).'/'.$denominator;

		$result_b = $this->result($fraction_values_a[1], $fraction_values_b[0]).'/'.$denominator;
				
		return [
			$result_a,
			$result_b
		];
	}
	
	public function multiple_denominators($values) {
		$cur_common = NULL;
		foreach($values as $key => $value) {
			if($key > 0) {
				if($cur_common == NULL) {
					$cur_common = $this->common_denominator($value, $values[$key-1]);
				} else {
					$cur_common = $this->common_denominator($value, $cur_common[0]);
				}
			}
		}
		foreach($values as $key => $value) {
			$fraction = $this->fraction_values($value);
			$common_fraction = $this->fraction_values($cur_common[0]);
			$multiplier = $this->execute_divide($common_fraction[1], $fraction[1])['value']; 			
			$result = $this->result($fraction[0], $multiplier).'/'.$this->result($fraction[1], $multiplier);
			$values[$key] = $result;	
		}
		return $values;
	}
	
	public function fraction_values($value) {
		return $object->strings->explode('/', $value);	
	}
	
	public function fraction_string($fraction) {
		return $fraction[0].'/'.$fraction[1];	
	}
	
	private function collect_results($intermediate_results, $base=10) {
		$result = '0';
		$fractions = [];
		foreach($intermediate_results as $result_value) {
			$result_value = $this->numeric_value($result_value);
			$result = $this->add($result, $result_value, $base);
		}
		
		return $result;
	}
	
	public function place_decimal($value, $length, $remove_decimal=false, $prefix=false) {
		$original_length_set = $length;
		if($remove_decimal && $object->strings->strpos($value, '.') != (-1)) {
			$split = $object->strings->explode('.', $value);	
			$value = $split[0].$split[1];
			
			$start_offset = $object->strings->strlen($split[0]);
			$length = ($object->strings->strlen($value) - ($start_offset+$length));
		} else if($length < 0) {
			$length = (-$length);
		}
		if($prefix && ($length >= ($object->strings->strlen($value)-1))) {
			$prepend = $length - ($object->strings->strlen($value)-1)+1;
			
			$counter = 0;
			while($counter < $prepend) {
				$value = '0'.$value;
				$counter++;	
			}
			
		} else if($length < 0 && $original_length_set > 0) {
			$append = -$length;			
			$counter = 0;
			while($counter < $append) {
				$value .= '0';
				$counter++;	
			}
		}
		$digits = $this->get_digits($value);
		$result = '';
		foreach($digits as $key => $digit) {
			if($key == ($length)) {
				$result = '.'.$result;	
			}
			$result = $digit.$result;
		}
		
		if($object->strings->strpos($result, '.') != (-1)) {
			$split = $object->strings->explode('.', $result);
			if($object->strings->strlen($split[1]) == 0) {
				$result = $split[0];	
			}
		}
		if($object->strings->strpos($result, '.') == (-1) && $object->strings->strlen($result) > 1 && $object->strings->substr($result, 0, 1) == 0) {
			$result = $object->strings->substr($result, 1);	
		}
		$result = $this->trim($result);
		return $result;
	}
	
	public function place_decimal_alt($value, $length, $remove_decimal=false, $prefix=false) {
		$original_length_set = $length;			
		$unaltered_length = $length;
		if($unaltered_length >= $object->strings->strlen($value)) {
			$prepend = $length - $object->strings->strlen($value);
			$counter = 0;
			while($counter <= $prepend) {
				$value = '0'.$value;
				$counter++;	
			}
			
		} else if($length < 0) {
			$append = -$length;			
			$counter = 0;
			while($counter < $append) {
				$value .= '0';
				$counter++;	
			}
		}
		$length = $unaltered_length;
		if($length < 0) {
			$remove_decimal = true;
		}
		$digits = $this->get_digits($value);
		$result = '';
		foreach($digits as $key => $digit) {
			if($key == ($length)) {
				if(!$remove_decimal) {
					$result = '.'.$result;	
				}
			}
			$result = $digit.$result;
		}
		if($length == $object->strings->strlen($result)) {
			$result = '0.'.$result;		
		}
		
		
		if($object->strings->strpos($result, '.') != (-1)) {
			$split = $object->strings->explode('.', $result);
			if($object->strings->strlen($split[1]) == 0) {
				$result = $split[0];	
			}
		}
		if($object->strings->strpos($result, '.') == (-1) && $object->strings->strlen($result) > 1 && $object->strings->substr($result, 0, 1) == 0) {
			$result = $object->strings->substr($result, 1);	
		}
		$result = $this->trim($result);
		return $result;
	}
	
	public function pad_zeros($value, $length, $reverse=false) {
		$counter = 0;
		while($counter < $length) {
			if(!$reverse) {
				$value = $value.'0';
			} else {
				$value = '0'.$value;
			}
			$counter++;	
		}
		return $value;
	}

	public function sub_divide($divider, $value, $change_base=false) {
		$base = 10;
		
		
		$test_sum = 0;
		

		$divider_length = $object->strings->strlen($divider);
		$divider = $this->remove_leading_zeros($divider, true);
		
		$divider_exponent_translation = $divider_length - $object->strings->strlen($divider);
		$divider_length = $object->strings->strlen($divider);
		
		$intermediate_results = [];
		$digits = $this->get_digits($value);
		foreach($digits as $exponent => $digit) {
			if($digit != '0') {
				$exponent_translation = 0;
				if($object->strings->strlen($digit) <= $divider_length) {
					$digit = $this->pad_zeros($digit, $divider_length);	
					if($digit < $divider) {
						$divider_length += 1;
					}
					$exponent_translation = $divider_length;
				}
				
				$exponent_alteration = 0;
				
				$exponent = $exponent-$exponent_translation-$divider_exponent_translation;				
				$result;
				$result = $digit / $divider;
				/*$result =  $math->divide($digit, $divider);*/
				

				$intermediate_results[] = [
					'value' => $result,
					'exponent' => $exponent
				];
			}
		}
		
		$result = $this->collect_results($intermediate_results, $base);
		
		return $result;
	}

	public function permutations($values) {
		$permutations_main = new permutations_main($values);
		return $permutations_main->generate();
	}
	
	public function combinations($values) {
		$combinations = new combinations($values);
		return $combinations->start();
	}

	public function combinations_alt($values) {
		$combinations = [$values];		
		foreach($values as $key => $value) {
			$sub_values = $values;
			delete ($sub_values[$key]);
			
			$combinations[] = $sub_values;	
			if($object->count($sub_values) > 0) {
				$sub_combinations = $this->combinations($sub_values);
				$combinations = $object->concat($combinations, $sub_combinations);	
			}
		}
		$result = [];
		foreach($combinations as $combination) {
			if(!$object->in_array($combination, $result) && $object->count($combination) > 0) {
				$result[] = $combination;	
			}
		}
		return $result;
	}
		
	public function _divisible($value, $divider) {
		$division = $this->execute_divide($value, $divider);
		if($division['remainder'] == 0) {
			return true;	
		}
		return false;
	}
	
	
	private function logarithm_sub($value, $base) {
		return $this->logarithm_base($value, $base);		
	}
	
	public function set_logarithm_precision($logarithm_precision) {
		$this->logarithm_iteration_count = $logarithm_precision;	
	}
	
	public function logarithm($value, $base=['value' => 2, 'remainder' => '0/1'], $iteration_count=NULL) {
		if($iteration_count === NULL) {
			$iteration_count = $this->logarithm_iteration_count;
		}
		
		if($base == 'e' || $this->fraction_values($base['remainder'])[0] != '0' || $base['value'] > 10) {
			return $this->logarithm_sub($value, $base);	
		}
		
		$altered_base;
		if($base['value'] != 10) {
			$altered_base = $this->change_base($value['value'], $base['value']);
		} else {
			$altered_base = $value['value'];	
		}
		$exponent = $object->strings->strlen($altered_base)-1;
		
		$divider = $this->execute_power_whole($base, $exponent);
		$division = $this->execute_divide($value, $divider);
		$fraction_values = $this->fraction_values($division['remainder']);
		$whole_part = ['value' => $division['value'], 'remainder' => '0/1'];
		$fraction_whole = ['value' => $division['value'], 'remainder' => '0/1'];
		$fraction_part = ['value' => 1, 'remainder' => $fraction_values[0].'/'.($this->result($fraction_values[1], $fraction_whole['value']))];
		
		
		$fraction_set = $fraction_part;
		
		
		$logarithm_common = $this->logarithm_sub($fraction_part, $base);
		
		
		$result = ['value' => $exponent, 'remainder' => '0/1'];		
		$log_whole_part = $this->logarithm_sub($fraction_whole, $base);
		
		
		$result = $this->add_total($result, $logarithm_common);
		$result = $this->add_total($result, $log_whole_part);
		return $result;
		
	}
	
	public function natural_logarithm($value) {
		$base_numerator = $this->subtract_total($value, ['value' => 1, 'remainder' => '0/1']);	
		$base_denominator = $this->add_total($value, ['value' => 1, 'remainder' => '0/1']);
		$base = $this->execute_divide($base_numerator, $base_denominator);
		
		$total_sum = $base;
		$counter = 3;
		while($counter < $this->logarithm_iteration_count) {
			$added_value = $this->power($base, ['value' => $counter, 'remainder' => '0/1']);
			$added_value = $this->execute_divide($added_value, $counter);
			
			$total_sum = $this->add_total($total_sum, $added_value); 
			if($this->truncate_fractions_length > 0) {
				$total_sum['remainder'] = $this->execute_shorten_fraction($total_sum['remainder']);	
			}
			$counter += 2;	
		}
		
		$total_sum = $this->multiply_total($total_sum, ['value' => 2, 'remainder' => '0/1']);
		return $total_sum;
	}
	
	private $logarithm_iteration_count = 12;
	
	public function logarithm_base($value, $base) {
		$natural_logarithm_value = $this->natural_logarithm($value);
		if($base == 'e') {
			return $natural_logarithm_value;	
		}
		$base_value = $this->natural_logarithm($base);
		$result = $this->execute_divide($natural_logarithm_value, $base_value);
		return $result;	
	}
	
	public function quick_fraction($value) {
		/*$values = $this->fraction_values($value);*/
		$result = $this->real_fraction($value, 15);
		return $result;	
	}
	
	public function absolute_fraction($value) {
		
		if($object->strings->strpos($value, '-') != (-1)) {
			$split = $object->strings->explode('-', $value);
			return $split[1];
		}
		return $value;
	}
	
	private $power_depth = 0;
	private $maximum_power = 24;
	
	
	public function whole_common($value) {
		$e_position = $object->strings->strpos($value, 'E');
		if($e_position != (-1)) {
			$e_translation = $object->strings->substr($value, $e_position+1);
			$value = $object->strings->substr($value, 0, $e_position);	
			$decimal_place = $object->strings->strpos($value, '.');
			$place = $decimal_place + $e_translation;
			$place = $object->strings->strlen($value)-1 - $place;
			
			$value = $this->place_decimal_alt($value, $place, false, true);
			
		}
		$negative = false;
		if($object->strings->strpos($value, '-') != (-1)) {
			$negative = true;	
		}
		$value = $this->absolute($value);
		if($object->strings->strpos($value, '.') != (-1)) {
			$split = $object->strings->explode('.', $value);
			$fraction = '0.'.$split[1];
			$common = $this->common($fraction);
			$result = [
				'value' => $this->absolute($split[0]),
				'remainder' => $this->absolute($common)
			];
		} else {
			if($value == '' || $value == NULL) {
				$value = '0';	
			}
			$result = [
				'value' => $value,
				'remainder' => '0/1'
			];
		}
		if($negative) {
			$result = $this->negative_value($result);	
		}
		return $result;
	}
	
	public function whole_numerator($value) {
		$fraction_values = $this->fraction_values($value['remainder']);
		$numerator = $this->add($fraction_values[0], $this->result($fraction_values[1], $value['value']));
		
		return $numerator.'/'.$fraction_values[1];	
	}
	

		
	public function factorial($value) {
		$factorial = new factorial($value, $this);
		$resolution = $factorial->resolve();
		return $resolution;
		/*return $this->partial_factorial($value);*/
	}
	
	public function partial_factorial($value, $stop=1) {
		if($value == 1 || $value == $stop) {
			return $value;	
		}
		return $this->result($value, $this->partial_factorial($this->subtract($value, 1), $stop));
	}
	
	private function gamma($n) {
		
		$sin_value = $this->multiply_total($this->pi(), $n);
		$sin_value = $this->quick_numeric($sin_value);
		$sin = $math->sin($sin_value);
		$sin = $this->whole_common($sin);
		$result = $this->execute_divide($this->pi(), $sin);
		
		
		return $result;
	}
	
	private function next_rational_root_sub($value, $set_power, $same) {
		$root = $math->pow($value, 1/$set_power);
		$root_floor = $this->floor($root);
		if($root == $root_floor) {
		} else {
			$root_floor = $this->add($root_floor, 1);
			
		}
		$value = $this->execute_power_whole(['value' => $root_floor, 'remainder' => '0/1'], ['value' => $set_power, 'remainder' => '0/1'])['value'];
		return ['value' => $value['value'], 'root' => $root_floor];
	}

	public function find_closest_root_multiplier($value, $power) {
		$start = ['value' => 2, 'remainder' => '0/1'];
		$multiplication = $this->execute_power_whole($start, $power);

		$last_start = $start;
		$last_mult = $multiplication;
		while($this->larger_total($value, $multiplication, false)) {
			$last_start = $start;

			$last_mult = $multiplication;
			$start = $this->multiply_total($start, ['value' => '2', 'remainder' => '0/1']);
			$multiplication = $this->execute_power_whole($start, $power);
		}

		return ['divider' => $last_mult['value'], 'source' => $last_start['value']];
	}

	public function partition_root($value, $power) {
		$closest_value = $this->find_closest_root_multiplier($value, $power);
		$division = $this->execute_divide($value, $closest_value['divider']);


		/*$root_first = $this->root($division['value'], $power);
		if($root_first === false) {
			$root_first = $this->root_closest_result;
		}*/

		/*$result_first = $this->execute_power_whole($root_first, $power)['value'];
		$result_alt = $closest_value['divider'];

		$result_total = $this->result($result_first, $result_alt);
		$root_source_result = $this->result($root_first, $closest_value['source']);
		if($result_total == $value) {
			return $root_source_result;
		}
		$division_remainder = $this->execute_divide($value, $result_total);
		$intermediate_result = $this->root($division_remainder['value'], $power);
		if($intermediate_result === false) {
			$intermediate_result = $this->root_closest_result;
		}
		return $this->result($root_source_result, $intermediate_result);*/
	}
	
	private $next_rational_root_start_first;
	private $next_rational_root_start_second;
	
	public function next_rational_root($value, $set_power, $same=true) {
		$root = $this->root($value, $set_power);
		
		if($root !== (-1)) {
			return ['root' => $root, 'value' => $value];	
		}
		$root = $this->root_closest_result;
		$root = $this->add($root, 1);
		return ['root' => $root, 'value' => $this->execute_power_whole($root, $set_power)['value']];
	}
	
	public function list_rational_roots($from, $to, $set_power=2) {
		if(!$this->larger($from, 1)) {
			$from = 1;
		}
		$root_results = [];
		$rational_root = $this->next_rational_root_list($from, $set_power, true, false);
		$root_results[] = $rational_root;
		while($this->larger($to, $rational_root['value'])) {
			$rational_root = $this->next_rational_root_list($this->add($rational_root['value'], 1), $set_power, true, true);
			if($this->larger($to, $rational_root['value'])) {
				$root_results[] = $rational_root;
			}
		}
		return $root_results;
	}
	
	public function next_rational_root_list($value, $set_power=2, $same=true, $previous_set_start=false) {
		
		$reverse = false;
		$length = $object->strings->strlen($value)-1;
		$power = 2;
		
		$execute_power = $set_power;
		$unaltered_power = $set_power;
		$set_power -= 2;
		$larger_root = true; 		
		if($larger_root) { 			
			$decimal_mult;
			$incremented;
			$decimal_mult_root;
			if(!$object->isset($this->next_rational_root_start_first)) { 				
				
				$start_root_prefix = $this->subtract($this->next_rational_root($value, $unaltered_power)['root'], 2);
				$start_root_prefix_unaltered = $this->result($start_root_prefix, $start_root_prefix);
				
				$decimal_mult_root = $start_root_prefix;				
				$decimal_mult;	
				
				
				$incremented_root = $this->add($decimal_mult_root, 1);
				$incremented = $this->execute_power_whole($incremented_root, $power)['value'];
				$decimal_mult = $start_root_prefix_unaltered;
				
				$set_root = $decimal_mult;
				$this->next_rational_root_start_first = ['value' => $start_root_prefix_unaltered, 'root' => $start_root_prefix];
				$this->next_rational_root_start_second = ['value' => $incremented, 'root' => $incremented_root];
			} else {
				$first = $this->next_rational_root_start_first;
				$second = $this->next_rational_root_start_second;
				$decimal_mult_root = $first['root'];
				$incremented = $second['value'];
				$decimal_mult = $first['value'];	
			}
			
			$next_root;
			$next_root = $this->add($this->subtract($this->result($incremented, $power), $decimal_mult), $power);
			$current_root_root = $this->add($decimal_mult_root, 2);
			
			$next_root_value;
			if($set_power >= 1) {
				$next_root_value = $this->execute_power_whole(['value' => $current_root_root, 'remainder' => '0/1'], $set_power);
				$next_root_value = $this->multiply_total($next_root_value, ['value' => $next_root, 'remainder' => '0/1'])['value'];
			} else {
				$next_root_value = $next_root;	
			}
			$set_root = $next_root_value;
			$count = 0;
			$store = $next_root;
			while(!$this->larger($next_root_value, $value)) { 				
				$store = $next_root;
				$next_root = $this->add($this->subtract($this->result($next_root, $power), $incremented), $power);	
				$current_root_root = $this->add($current_root_root, 1);
				
				if($set_power >= 1) {
					$next_root_value = $this->execute_power_whole(['value' => $current_root_root, 'remainder' => '0/1'], $set_power);
					$next_root_value = $this->multiply_total($next_root_value, ['value' => $next_root, 'remainder' => '0/1'])['value'];
				} else {
					$next_root_value = $next_root;	
				}
				if($this->larger($next_root_value, $value, false)) { 					
					$set_root = $next_root_value;				
				}
				$incremented = $store;
				
			}
			
			return ['value' => $set_root, 'root' => $current_root_root];
		} else {
			$counter = 2;
			$root = $this->result($counter, $counter);
			$root = $this->execute_power_whole(['value' => $counter, 'remainder' => '0/1'], ['value' => $execute_power, 'remainder' => '0/1']);
			while($this->larger_total(['value' => $value, 'remainder' => '0/1'], $root, !$same)) {
				$counter++;	
				$root = $this->execute_power_whole(['value' => $counter, 'remainder' => '0/1'], ['value' => $execute_power, 'remainder' => '0/1']);
			}
			return ['value' => $root['value'], 'root' => $counter];

		}
	}
	
	private function preprocess_power($value, $power) {
		$value_fraction = $this->fraction_values($value['remainder']);
		if($object->strings->strlen($value['value']) > 255) {			
			$rational_root = $this->next_rational_root($value['value'], $power, true);
			$rational_root_sqrt = $rational_root['root'];
			$rational_root = $rational_root['value'];
			$division_part = $this->execute_divide(['value' => $rational_root, 'remainder' => '0/1'], $value);
			$result = ['value' => $rational_root_sqrt, 'remainder' => '0/1'];
			
			$quick_fraction = $this->numeric_whole($division_part['value'], $this->quick_fraction($division_part['remainder']));
			
			$part_result = $this->execute_power($division_part, $power);
			
			$result = $this->execute_divide($result, $part_result, true);
			return $result;
		} else {
			return $this->execute_power($value, $power);
		}
	}
	
	private function intermediate_process_power($value, $power) {
		$fraction_values = $this->fraction_values($value['remainder']);
		$denominator_root = $this->next_rational_root($fraction_values[1], $power);
		if($denominator_root['value'] == $fraction_values[1]) {
			$whole_value = $this->make_whole($value);
			$value_root = $this->next_rational_root($whole_value['value'], $power);
			if($whole_value['value'] == $value_root['value']) {
				$division = $this->execute_divide($value_root['root'], $denominator_root['root']);
				return $division;
			}
		}
		return $this->execute_power($value, $power);
	}
	
	public function power($value, $power) {
		$negative_power = false;
		if($this->negative($power)) {
			$negative_power = true;
			$power = $this->absolute($power);	
		}
		$power_fraction_values = $this->fraction_values($power['remainder']);
		$result_fraction = ['value' => 1, 'remainder' => '0/1'];
		if($power_fraction_values[0] != '0') {
			$result_fraction = $this->preprocess_power($value, $power_fraction_values[1]); 	
			if($power_fraction_values[0] != 1) {
				$result_fraction = $this->execute_power_whole($result_fraction, $power_fraction_values[0]);
			}
		}
		$result_whole = ['value' => 1, 'remainder' => '0/1'];
		if($power['value'] != '0') {
			$result_whole = $this->execute_power_whole($value, ['value' => $power['value']]);
		}
		$result = $this->multiply_total($result_whole, $result_fraction);
		
		if($negative_power) {
			$result = $this->execute_divide(1, $result);
		}
		return $result;
	}

	
	public function execute_power_whole($value, $power) {
		if(!$object->item_is_array($power)) {
			$power = ['value' => $power, 'remainder' => '0/1'];	
		}
		if(!$object->item_is_array($value)) {
			$value = ['value' => $value, 'remainder' => '0/1'];	
		}
		if($power['value'] == 1) {
			return $value;	
		}
		if($power['value'] == 0) {
			return ['value' => '1', 'remainder' => '0/1'];	
		}
		/*return $math->execute_power_whole($value, $power);*/
		/*if($this->larger($power['value'], 6)) {
			$power_partition = $this->execute_divide($power['value'], 6);
			$set_power = $power_partition['value'];
			$remainder = $this->subtract($power['value'], $this->result($set_power, 6));
			$intermediate_result = $this->execute_power_whole($value, ['value' => $set_power, 'remainder' => '0/1']);
			$power_max = 5;			
			$part_result = $intermediate_result;
			$counter = 0;
			while($this->larger($power_max, $counter, false)) {
				$part_result = $this->multiply_total($part_result, $intermediate_result);
				$counter = $this->add($counter, 1);	
			}
			$counter = 0;
			while($this->larger($remainder, $counter, false)) {
				$part_result = $this->multiply_total($part_result, $value);
				$counter = $this->add($counter, 1);	
			}
			return $part_result;
		} else {*/
			$counter = 0;
			$result = [...$value];
			$power_max = $this->subtract($power['value'], 1);
			while($this->larger($power_max, $counter, false)) {
				$result = $this->multiply_total($result, $value);
				$counter = $this->add($counter, 1);	
			}
			return $result;
		/*}	*/
	}
	
	private $make_whole_multiplier = 1;

	public function make_whole($value) {
		$denominator_fraction_values = $this->fraction_values($value['remainder']);
		$multiplier = ['value' => '0', 'remainder' => $denominator_fraction_values[1].'/1']; 		
		$denominator_division = $this->multiply_total($multiplier, $value);
		$this->make_whole_multiplier = $denominator_fraction_values[1];
		$value = $denominator_division;
		return $value;
	}
	
	public function get_root_closest_result() {
		return $this->root_closest_result;	
	}
	
	public $root_closest_result;
	
	public function root($x, $n) {
		/*$intermediate_result = $math->root($x, $n);
		if($intermediate_result['exact']) {
			$this->root_closest_result = $intermediate_result['value'];
			return $this->root_closest_result;
		} else {
			$this->root_closest_result = $intermediate_result['closestResult'];
			return false;
		}*/

		$x = $this->absolute($x);
		if($x == 1) {
			$this->root_closest_result = '1';
			return '1';	
		} else if($x == 0) {
			$this->root_closest_result = '0';
			return '0';	
		}
		$root_result = $this->root_sub($x, $n);
		$result = $this->execute_power_whole($root_result, $n)['value'];
		if($x == $result) {
			$this->root_closest_result = $root_result;
			return $root_result;	
		}
		$root_result = $this->subtract($root_result, 1);
		$this->root_closest_result = $root_result;
		return (-1);
	}
	
	private function root_sub($x, $n) {
		$guess = '1';
		$step = 1;
		$counter = 0;
		while(true) {
			$w = $this->execute_power_whole($this->add($guess, $step), $n)['value'];
			if($w == $x) {
				return $this->add($guess, $step);			
			} else if($this->larger($x, $w)) {
				/*$step = $this->bit_shift($step, 1);	*/
				$step = $this->result($step, 2);
			} else if($step == 1) {
				return $this->add($guess, 1);	
			} else {
				/*$guess = $this->add($guess, $this->bit_shift_right($step, 0));*/

				$guess = $this->add($guess, $this->execute_divide($step, 2)['value']);
				$step = 1;	
			}
			/*$guess = $this->modulus($x, $step);*/
			/*$guess = $this->result($w, $step);*/
		}
	}
	
	public $root_fraction_precision = ['value' => '0', 'remainder' => '1/100'];
	public $maximum_root_fraction_iterations = 0;
	
	public function root_fraction($number, $root, $p=NULL, $shorten=true) {
		/*if($p == NULL) {
			$p = $this->root_fraction_precision;
		}
		return $math->root_fraction($number, $root, $p, $this->truncate_fractions_length);*/
		$x = [];
		if(!$object->item_is_array($number)) {
			$number = ['value' => $number, 'remainder' => '0/1'];	
		}
		if($shorten) {
			$number['remainder'] = $this->execute_shorten_fraction($number['remainder']);
		}
		if($p == NULL) {
			$p = $this->root_fraction_precision;	
		}
		$whole = $this->whole_numerator($number);
		$root_solver = new root_solver($whole, $root, $this);
		$num = $root_solver->approximate_value();

		
		$x[] = $num;
		$x[] = $this->execute_divide($num, $root);		
		$root = ['value' => $root, 'remainder' => '0/1'];
		$counter = 0;
		$subtraction_precision_value = $this->absolute($this->subtract_total($x[1], $x[0]));
		/*$object->log($object->toJSON(['precision' => $subtraction_precision_value]));*/
		while($this->larger_total($subtraction_precision_value, $p)) {
			$x[0] = $x[1];
						
			$root_term = $this->subtract_total($root, ['value' => 1, 'remainder' => '0/1']);
			$first_term = $this->multiply_total($root_term, $x[1]);

			/*$object->log($object->toJSON(['in1' => [$root_term, $first_term]]));*/
			
			$numerator = $number;			
			$denominator = $this->execute_power_whole($x[1], $root_term);

			/*$object->log($object->toJSON(['in2' => [$numerator, $denominator]]));*/
			
			$second_term = $this->execute_divide($numerator, $denominator);


			/*$object->log($object->toJSON(['in3' => [$second_term]]));*/

			$total_term = $this->add_total($first_term, $second_term);

			/*$object->log($object->toJSON(['in4' => [$total_term]]));*/
			
			$x[1] = $this->execute_divide($total_term, $root);
			if($this->truncate_fractions_length > 0) {
				$x[1]['remainder'] = $this->execute_shorten_fraction($x[1]['remainder']);	
			}
			/*$object->log($object->toJSON(['in5' => [$x]]));*/
			$subtraction_precision_value = $this->absolute($this->subtract_total($x[1], $x[0]));
			/*$object->log($object->toJSON(['precision' => $subtraction_precision_value]));*/
		}
		return $x[1];
	} 
	
	private function square_root($value) {
		return $this->root($value, 2);
	}
	
	private function cubic_root($value) {
		return $this->root($value, 3);
	}
	
	public function trim($value) {
		$digits = $object->strings->str_split($value);
		$remove = 0;
		$decimal_point_found = false;
		foreach($digits as $index => $digit) {
			if(!$decimal_point_found) {
				$remove++;	
			}
			if($digit == '.' || $digit != '0') {
				$decimal_point_found = true;	
			}
		}
		$remove -= 1;
		$value = $object->strings->substr($value, $remove);
		return $value;
	}

	public function find_continued_fraction($value, $power, $limit, $precision=NULL) {
		/*$math->assign_truncate_fractions($this->truncate_fractions_length);
		return $math->find_continued_fraction($value, $power, $limit, $precision);*/
		$root_solver = new root_solver(NULL, $power, $this);
		$result = $root_solver->solve_root($value, $limit, $precision);	
		return $result;
	}
	
	public function square_root_fraction($value, $limit=30) {
		$sqrt = $this->root($value, 2);
		$m = $this->root_closest_result;
		
		
		$first_m = $m;
		
		$continued_fraction = [$m];
			
		
		$x_denominator_value = $this->result($m, $m);
		$x_denominator = $this->subtract($value, $x_denominator_value);	
		
		$x_numerator = $this->add($m, $m);
		
		$x_division = $this->execute_divide($x_numerator, $x_denominator);
		
		$continued_fraction[] = $x_division['value'];
		
		$counter = 0;
		$last_x_denominator = $x_denominator;
		while($counter < $limit) {
			
			$x_denominator_value = $this->subtract($first_m, $this->fraction_values($x_division['remainder'])[0]);
			$x_numerator_value = $x_denominator;
			$x_denominator_subtraction = $this->result($x_denominator_value, $x_denominator_value);
			$x_denominator = $this->subtract($value, $x_denominator_subtraction);	
			
			$fraction_value = $x_numerator_value.'/'.$x_denominator;
			$fraction_value = $this->execute_shorten_fraction($fraction_value, true);
			
			$fraction_values = $this->fraction_values($fraction_value);
			$x_numerator_multiplier = $fraction_values[0];
			$x_denominator = $fraction_values[1];
			$x_numerator = $this->result($x_numerator_multiplier, $x_denominator_value);
			
			$m = $x_numerator;
			
			$x_denominator_value = $this->result($m, $m);	
			
			$x_numerator = $this->add($first_m, $m);
			
			$x_division = $this->execute_divide($x_numerator, $x_denominator);
			
			$continued_fraction[] = $x_division['value'];
			
			$periodic = $this->detect_period_continued_fraction($continued_fraction);
			if($periodic !== false) {
				return $periodic;	
			}
			
			$last_x_denominator = $x_denominator;
			$counter++;
		}
		return $continued_fraction;
	}
	
	private function e_terms($count) {
		$n = 0;
		$result = [2, 1];
		$start = 2;
		$counter = 0;
		while($counter < $count) {
			$result[] = $start;
			$result[] = 1;
			$result[] = 1;
			$start = $this->add($start, 2);
			$counter++;	
		}
		return $result;	
	}
	
	public function detect_period_continued_fraction($continued_fraction) {
		$start_value = $object->array_shift($continued_fraction);
		$stop_value = $this->result($start_value, 2);
		
		$period_values = [];
		$stop = false;
		foreach($continued_fraction as $value) {
			if(!$stop) {
				if($value == $stop_value) {
					$stop = true;	
				} else {				
					$period_values[] = $value;	
				}
			}
		}
		if(!$stop) {
			return false;	
		}
		$first_part = [];
		$second_part = [];
		$values_count = $this->floor($object->count($period_values) / 2);
		$stop_value_found = false;
		foreach($continued_fraction as $index => $value) {
			if($value == $stop_value) {
				$stop_value_found = true;	
			} else {
				if(!$stop_value_found) {
					$first_part[] = $value;	
				} else {
					$second_part[] = $value;	
				}
			}
		}
		if($first_part == $second_part) {
			$object->array_unshift($first_part, $start_value);
			$first_part[] = $stop_value;
			return $first_part;	
		}
		return false;
	}

	
	private $continued_fraction_resolution_level = 0;
	private $set_continued_fraction_resolution_level = 12;
	
	private $set_continued_fraction_resolution_level_setting = 12;
	
	public function set_periodic_continued_fraction_precision($precision) {
		$this->set_continued_fraction_resolution_level_setting = $precision;
	}
	
	private $current_continued_fraction;
	
	public function resolve_continued_fraction($continued_fraction, $value=NULL) {
		$this->continued_fraction_resolution_level = 0;
		if($value != NULL) {
			$this->set_continued_fraction_resolution_level = $this->set_continued_fraction_resolution_level_setting;
		} else {
			$this->set_continued_fraction_resolution_level = 1;
		}
		$this->current_continued_fraction_whole = $continued_fraction;
		$first_value = ['value' => $object->array_shift($continued_fraction), 'remainder' => '0/1'];
		$this->current_continued_fraction = $continued_fraction;
		$this->current_continued_fraction_squared_value = $value;
		
		return $this->add_total($first_value, $this->execute_divide(['value' => 1, 'remainder' => '0/1'], $this->resolve_continued_fraction_sub($continued_fraction)));
	}
	
	private function resolve_continued_fraction_sub($continued_fraction) {
		$first_value = ['value' => $object->array_shift($continued_fraction), 'remainder' => '0/1'];
		if($object->count($continued_fraction) == 0) {
			$continued_fraction = $this->current_continued_fraction;
			$this->continued_fraction_resolution_level++;
			if($this->current_continued_fraction_squared_value == NULL) {
				return $first_value;
			} else if($this->continued_fraction_resolution_level == $this->set_continued_fraction_resolution_level) {
				$result = $this->add_total($first_value, $this->execute_divide(['value' => 1, 'remainder' => '0/1'], $this->terminating_continued_fraction($this->current_continued_fraction_whole, $this->current_continued_fraction_squared_value)));	
				return $result;
			}
		}
		$result = $this->add_total($first_value, $this->execute_divide(['value' => 1, 'remainder' => '0/1'], $this->resolve_continued_fraction_sub($continued_fraction)));
		return $result;
	}
	
	public function terminating_continued_fraction_values($continued_fraction, $variable=false) {
		if($variable) {
			$object->array_pop($continued_fraction);
			$continued_fraction[] = 1;	
		}
		$values = $continued_fraction;
		$values = $object->reverse($values);
		
		$value = $object->array_shift($values);
		$intermediate_result = '1/'.$value;
		
		while($object->count($values) > 0) {
			$next_value = $object->array_shift($values);
			$numerator = $this->result($value, $next_value);
			$fraction_addition = $numerator.'/'.$value;
			
			$intermediate_result = $this->add_fraction($intermediate_result, $fraction_addition);
			$intermediate_result = $this->flip_fraction($intermediate_result);
			$value = $next_value;
		}
		return $intermediate_result;
	}
	
	public function terminating_continued_fraction($continued_fraction, $value) {
		$variable_values = $this->terminating_continued_fraction_values($continued_fraction, true); 		
		$constant_values = $this->terminating_continued_fraction_values($continued_fraction);			
		
		$variable_values = $this->fraction_values($variable_values);
		$constant_values = $this->fraction_values($constant_values);
		
		
		$a = ['value' => $variable_values[0], 'remainder' => '0/1'];
		$c = ['value' => $variable_values[1], 'remainder' => '0/1'];
		$b = ['value' => $constant_values[0], 'remainder' => '0/1'];
		$d = ['value' => $constant_values[1], 'remainder' => '0/1'];
		
		$y_approximate = $value;		
		
		$numerator = $this->multiply_total($d, $y_approximate);
		$numerator = $this->subtract_total($b, $numerator);
		$denominator = $this->multiply_total($c, $y_approximate);
		$denominator = $this->subtract_total($denominator, $a);
		
		$result = $this->execute_divide($numerator, $denominator);
		return $result;
			
	}
		
	public function perform_cfa_vector($cf) {
		$counter = 0;
		$cfa_continued_fraction_result = [];
		while($counter < 15) {
			$counter2 = 0;
			$continue = true;
			while($continue) {
				while($this->cfa_need_term()) {
					if($object->count($cf[$this->cfn]) > 0) {
						$term = $object->array_shift($cf[$this->cfn]);
						$this->consume_term($term);	
					} else {
						$this->consume_term();	
					}
				}
				if($this->cfa_have_term) {
					$this->cfa_have_term = false;
					$cfa_continued_fraction_result[] = $this->cfa_this_term;
				}
				$counter2++;

				if($counter2 < 10) {
					$continue = true;
				} else {
					$continue = false;
				}
			}
			$counter++;
		}
		return $cfa_continued_fraction_result;
	}


	
	private $cfa_vector = [
		'a1' => 0,
		'a' => 0,
		'b1' => 0,
		'b' => 0,
		't' => 0
	];
	
	private $cfa_this_term;
	private $cfa_have_term = false;
	
	private $cfa_matrix = [
		'a12' => 0,
		'a1' => 0,
		'a2' => 0,
		'a' => 0,
		'b12' => 0,
		'b1' => 0,
		'b2' => 0,
		'b' => 0,
		't' => 0
	];
	
	private $cfn = 0;
	private $ab = ['value' => 0, 'remainder' => '0/1'];
	private $a1b1 = ['value' => 0, 'remainder' => '0/1'];
	private $a2b2 = ['value' => 0, 'remainder' => '0/1'];
	private $a12b12 = ['value' => 0, 'remainder' => '0/1'];
	
	public function choose_cfn() {
		$subtraction_a = $this->absolute($this->subtract_total($this->a1b1, $this->ab));
		$subtraction_b = $this->absolute($this->subtract_total($this->a2b2, $this->ab));	
		
		if($this->larger_total($subtraction_a, $subtraction_b)) {
			return 0;	
		}
		return 1;
	}
	
	public function cfa_matrix($a12, $a1, $a2, $a, $b12, $b1, $b2, $b) {
		$this->cfa_matrix['a12'] = $a12;
		$this->cfa_matrix['a1'] = $a1;
		$this->cfa_matrix['a2'] = $a2;
		$this->cfa_matrix['a'] = $a;
		$this->cfa_matrix['b12'] = $b12;
		$this->cfa_matrix['b1'] = $b1;
		$this->cfa_matrix['b2'] = $b2;
		$this->cfa_matrix['b'] = $b;
	}


	
	public function cfa_need_term() {
		if($this->cfa_matrix['b1'] == 0 && $this->cfa_matrix['b'] == 0 && $this->cfa_matrix['b2'] == 0 && $this->cfa_matrix['b12'] == 0) {
			return false;	
		}
		if($this->cfa_matrix['b'] == 0) {
			if($this->cfa_matrix['b2'] == 0) {
				$this->cfn = 0;	
			} else {
				$this->cfn = 1;	
			}
			return true;
		} else {
			$this->ab = $this->execute_divide($this->cfa_matrix['a'], $this->cfa_matrix['b']);
		}
		if($this->cfa_matrix['b2'] == 0) {
			$this->cfn = 1;
			return true;	
		} else {
			$this->a2b2 = $this->execute_divide($this->cfa_matrix['a2'], $this->cfa_matrix['b2']);	
		}
		if($this->cfa_matrix['b1'] == 0) {
			$this->cfn = 0;
			return true;	
		} else {
			$this->a1b1 = $this->execute_divide($this->cfa_matrix['a1'], $this->cfa_matrix['b1']);	
		}
		if($this->cfa_matrix['b12'] == 0) {
			$this->cfn = $this->choose_cfn();
			return true;	
		} else {
			$this->a12b12 = $this->execute_divide($this->cfa_matrix['a12'], $this->cfa_matrix['b12']);	
		}
		$this->cfa_this_term = $this->ab['value'];
		if($this->cfa_this_term == $this->a1b1['value'] && $this->cfa_this_term == $this->a2b2['value'] && $this->cfa_this_term == $this->a12b12['value']) {
			$this->cfa_matrix['t'] = $this->cfa_matrix['a'];
			$this->cfa_matrix['a'] = $this->cfa_matrix['b'];
			$this->cfa_matrix['b'] = $this->subtract($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b'], $this->cfa_this_term));
			$this->cfa_matrix['t'] = $this->cfa_matrix['a1'];
			$this->cfa_matrix['a1'] = $this->cfa_matrix['b1'];
			$this->cfa_matrix['b1'] = $this->subtract($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b1'], $this->cfa_this_term));
			$this->cfa_matrix['t'] = $this->cfa_matrix['a2'];
			$this->cfa_matrix['a2'] = $this->cfa_matrix['b2'];
			$this->cfa_matrix['b2'] = $this->subtract($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b2'], $this->cfa_this_term));
			$this->cfa_matrix['t'] = $this->cfa_matrix['a12'];
			$this->cfa_matrix['a12'] = $this->cfa_matrix['b12'];
			$this->cfa_matrix['b12'] = $this->subtract($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b12'], $this->cfa_this_term));
			$this->cfa_have_term = true;
			return false;
		}
		$this->cfn = $this->choose_cfn();
		return true;
	}
	
	public function consume_term($n=NULL) {
		if($n == NULL) {
			if($this->cfn == 0) {
				$this->cfa_matrix['a'] = $this->cfa_matrix['a1'];
				$this->cfa_matrix['a2'] = $this->cfa_matrix['a12'];
				$this->cfa_matrix['b'] = $this->cfa_matrix['b1'];
				$this->cfa_matrix['b2'] = $this->cfa_matrix['b12'];	
			} else {
				$this->cfa_matrix['a'] = $this->cfa_matrix['a2'];
				$this->cfa_matrix['a1'] = $this->cfa_matrix['a12'];
				$this->cfa_matrix['b'] = $this->cfa_matrix['b2'];
				$this->cfa_matrix['b1'] = $this->cfa_matrix['b12'];	
			}
		} else {
			if($this->cfn == 0) {
				$this->cfa_matrix['t'] = $this->cfa_matrix['a'];
				$this->cfa_matrix['a'] = $this->cfa_matrix['a1'];
				$this->cfa_matrix['a1'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['a1'], $n));	
				$this->cfa_matrix['t'] = $this->cfa_matrix['a2'];
				$this->cfa_matrix['a2'] = $this->cfa_matrix['a12'];
				$this->cfa_matrix['a12'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['a12'], $n));	
				$this->cfa_matrix['t'] = $this->cfa_matrix['b'];
				$this->cfa_matrix['b'] = $this->cfa_matrix['b1'];
				$this->cfa_matrix['b1'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b1'], $n));	
				$this->cfa_matrix['t'] = $this->cfa_matrix['b2'];
				$this->cfa_matrix['b2'] = $this->cfa_matrix['b12'];
				$this->cfa_matrix['b12'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b12'], $n));	
			} else {
				$this->cfa_matrix['t'] = $this->cfa_matrix['a'];
				$this->cfa_matrix['a'] = $this->cfa_matrix['a2'];
				$this->cfa_matrix['a2'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['a2'], $n));	
				$this->cfa_matrix['t'] = $this->cfa_matrix['a1'];
				$this->cfa_matrix['a1'] = $this->cfa_matrix['a12'];
				$this->cfa_matrix['a12'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['a12'], $n));	
				$this->cfa_matrix['t'] = $this->cfa_matrix['b'];
				$this->cfa_matrix['b'] = $this->cfa_matrix['b2'];
				$this->cfa_matrix['b2'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b2'], $n));	
				$this->cfa_matrix['t'] = $this->cfa_matrix['b1'];
				$this->cfa_matrix['b1'] = $this->cfa_matrix['b12'];
				$this->cfa_matrix['b12'] = $this->add($this->cfa_matrix['t'], $this->result($this->cfa_matrix['b12'], $n));	
			}
		}
	}
		
	public function cfa_vector($a1, $a, $b1, $b) {
		$this->cfa_vector['a1'] = $a1;
		$this->cfa_vector['a'] = $a;
		$this->cfa_vector['b1'] = $b1;
		$this->cfa_vector['b'] = $b;	
	}
	
	public function ingress($n) {
		$a = $this->cfa_vector['a'];
		$b = $this->cfa_vector['b'];
		
		$this->cfa_vector['a'] = $this->cfa_vector['a1'];
		$this->cfa_vector['a1'] = $this->add($a, $this->result($this->cfa_vector['a1'], $n)); 		$this->cfa_vector['b'] = $this->cfa_vector['b1'];
		$this->cfa_vector['b1'] = $this->add($b, $this->result($this->cfa_vector['b1'], $n)); 	}
	
	public function need_term() {
		return ($this->cfa_vector['b'] == 0 || $this->cfa_vector['b1'] == 0) 
		|| !($this->execute_divide($this->cfa_vector['a'], $this->cfa_vector['b'])['value'] == $this->execute_divide($this->cfa_vector['a1'], $this->cfa_vector['b1'])['value']);	
	}
	
	public function egress() {
		$n = $this->execute_divide($this->cfa_vector['a'], $this->cfa_vector['b'])['value'];
		
		
		$a = $this->cfa_vector['a'];
		$a1 = $this->cfa_vector['a1'];
		
		$this->cfa_vector['a'] = $this->cfa_vector['b'];
		$this->cfa_vector['b'] = $this->subtract($a, $this->result($this->cfa_vector['b'], $n));	
		$this->cfa_vector['a1'] = $this->cfa_vector['b1'];
		$this->cfa_vector['b1'] = $this->subtract($a1, $this->result($this->cfa_vector['b1'], $n));	
		return $n;
	}
	
	public function egress_done() {
		if($this->need_term()) {
			$this->cfa_vector['a'] = $this->cfa_vector['a1'];
			$this->cfa_vector['b'] = $this->cfa_vector['b1'];	
		}
		return $this->egress();
	}
	
	public function done() {
		return 	$this->cfa_vector['b'] == 0 && $this->cfa_vector['b1'] == 0;
	}
	
	public function perform_continued_fraction_arithematic($continued_fraction) {
		$result = [];
		foreach($continued_fraction as $value) {
			if(!$this->need_term()) {
				$result[] = $this->egress();
			}
			$this->ingress($value);
		}
		$stop = false;
		while(!$stop) {
			$result[] = $this->egress_done();
			if($this->done()) {
				$stop = true;	
			}
		}
		return $result;
	}
	
	private $cfa_continued_fraction_result;
	
	
	
	public function cf_add($cf_x, $cf_y) {
		return $this->cf_arithematic(0, 1, 1, 0,
									 0, 0, 0, 1,
									 $cf_x, $cf_y);	
	}
	
	public function cf_subtract($cf_x, $cf_y) {
		return $this->cf_arithematic(0, 1, -1, 0,
									 0, 0, 0, 1,
									 $cf_x, $cf_y);	
	}
	
	public function cf_multiply($cf_x, $cf_y) {
		return $this->cf_arithematic(1, 0, 0, 0,
									 0, 0, 0, 1,
									 $cf_x, $cf_y);	
	}
	
	public function cf_divide($cf_x, $cf_y) {
		return $this->cf_arithematic(0, 1, 0, 0,
									 0, 0, 1, 0,
									 $cf_x, $cf_y);	
	}
	
	public function flip_fraction($value) {
		$fraction_values = $this->fraction_values($value);
		return $fraction_values[1].'/'.$fraction_values[0];	
	}

	public function execute_power_alter_a($value) {
		$fraction_values = $this->fraction_values($value);
		$k = $fraction_values[0];
		$m = $fraction_values[1];
		$km_root = $this->root($this->result($k, $m), 2);
		if($km_root !== (-1)) {
			return $this->execute_divide($k, $km_root);
		}
		return (-1);
	}

	public function factor_root($value, $power) {
		$root_solver = new root_solver($this->whole_numerator($value), $power, $this);
		$result = $root_solver->factor_root();	
		return $result;
	}
	
	public function solve_remainder_square($value, $remainder_squared) {
		$root_solver = new root_solver(NULL, NULL, $this);
		$result = $root_solver->solve_r_square($value, $remainder_squared);	
		return $result;
	}
	
	public function reuse_square_root($value, $known_root) {
		$root_solver = new root_solver($this->whole_numerator($value), 2, $this);
		$result = $root_solver->solve($known_root);	
		return $result;
	}
	
	public function root_by_denominator($value, $denominator_root, $power) {
		$root_solver = new root_solver($this->whole_numerator($value), $power, $this);
		$result = $root_solver->root_by_denominator($denominator_root);
		return $result;
	}


	private $maximum_exponent_count = 25;
	public $execute_power_approximate_flag = false;
	public $disable_built_in_approximation;
	public $disable_exact_root_results = false;
		
	public function execute_power($value, $power) { 		
		if($this->disable_exact_root_results) {
			/*if(!$this->disable_built_in_approximation) {
				$quick_fraction = $this->quick_numeric($value);
				$approximate_value = $math->pow($quick_fraction, 1/$power); 					
				$approximate_value = $this->whole_common($approximate_value);
				return $approximate_value;
			}*/
			return $this->root_fraction($value, $power);
		}
		$this->execute_power_approximate_flag = false;
		
		$whole_numerator = $this->whole_numerator($value);
		$whole_values = $this->fraction_values($whole_numerator);
		$root_numerator = $this->next_rational_root($whole_values[0], $power);
		$root_denominator = $this->next_rational_root($whole_values[1], $power);
		if($root_numerator['value'] == $whole_values[0] && $root_denominator['value'] == $whole_values[1]) {
			$division = $this->execute_divide($root_numerator['root'], $root_denominator['root']);
			return $division;
		}
		if($power == 2) {
			$alter_result = $this->execute_power_alter_a($whole_numerator);
			if($alter_result !== (-1)) {
				return $alter_result;	
			}
		}
		if($this->negative($value)) {
			return NULL;	
		}
		if($power == 1) {
			return $value;	
		}
		
		
		$unshortened_value = $value;
		
		
			
		
		$approximate_value;
		
		$division_root = $this->root($value['value'], $power);
		$base_root = $this->root_closest_result;
		
		if($base_root == '0') {
			/*if($this->disable_built_in_approximation || $object->strings->strlen($whole_values[0]) > $this->maximum_divider_exponent || $object->strings->strlen($whole_values[1]) > $this->maximum_divider_exponent) {*/
				$numerator_root = $this->root_fraction($whole_values[0], $power);
				$denominator_root = $this->root_fraction($whole_values[1], $power);
				return $this->execute_divide($numerator_root, $denominator_root);	
			/*} else {		
				$quick_fraction = $this->quick_numeric($value);
				$approximate_value = $math->pow($quick_fraction, 1/$power); 					
				$approximate_value = $this->whole_common($approximate_value);
				return $approximate_value;
			}*/
		}
		
		$whole = $whole_numerator;		
		$counter = 0;
		$continue = true;
		
		$subtraction_value = $this->execute_power_whole(['value' => $base_root, 'remainder' => '0/1'], $power);
		
		
		$subtraction = $this->subtract_total($value, $subtraction_value);
		$division = $this->execute_divide($value, $subtraction_value);
		
		if($counter > 0) {
			
			$continue = false;
		}
		
		if($subtraction['value'] == 0 && $this->fraction_values($subtraction['remainder'])[0] == 0) {
			return ['value' => $base_root, 'remainder' => '0/1'];	
		}
		
		
		$division_whole_fraction = $this->whole_numerator($division);
		$division_whole_fraction_values = $this->fraction_values($division_whole_fraction);
		
		
		$subtraction_whole_fraction = $this->whole_numerator($subtraction);
		$subtraction_whole_fraction_values = $this->fraction_values($subtraction_whole_fraction);
		
		$ratio = $this->execute_divide($division_whole_fraction_values[1], $subtraction_whole_fraction_values[1])['value'];
		$subtraction_whole_fraction_values[0] = $this->result($subtraction_whole_fraction_values[0], $ratio);
		$subtraction_whole_fraction_values[1] = $this->result($subtraction_whole_fraction_values[1], $ratio);
		
		
		$f = $subtraction_whole_fraction_values[0];
		$b = $subtraction_whole_fraction_values[1];
		$k = $division_whole_fraction_values[0];
		$m = $division_whole_fraction_values[1];	
		$k_multiplier = $k;
		
		
		
		
		$v = $this->execute_power_whole($base_root, $power)['value'];
		$bv = $this->result($b, $v);
		$s = $this->add($bv, $f);
		$s_root = $this->root($s, $power);			
		$d = $this->root($b, $power);			
		if($d === (-1)) {
			$d = $b;
			$multiplier = $this->execute_power_whole(['value' => $b, 'remainder' => '0/1'], $power-1)['value'];
			$f = $this->result($f, $multiplier);
			$b = $this->result($b, $multiplier);
			$k = $this->result($k, $multiplier);
			$m = $this->result($m, $multiplier);
			$v = $this->execute_power_whole($base_root, $power)['value'];
			$bv = $this->result($b, $v);
			$s = $this->add($bv, $f);
			$s_root = $this->root($s, $power);			
		}
		$d_primary = $d;
		$s_primary = $s_root;
		
		 
		
		if($s_root !== (-1) && $d !== (-1)) {
			$s_division = $this->execute_divide($s_root, $d);
			$root = $s_division;
			return $root;	
		} else {
			
			
			
			$k_unaltered = $k;
			$m_unaltered = $m;
			
			$s;
			
			$k_root = $this->root($k, $power);				
			$k_root_secondary = $this->result($k, $this->result($base_root, $base_root));
			$k_root_secondary = $this->root($k_root_secondary, $power);				
			if($k_root_secondary !== (-1)) {
				$k_root = $k_root_secondary;	
				$k_root_secondary = NULL;
			}
			if($k_root === (-1)) {
				$k_root = $k;
				$k_multiplier = $this->execute_power_whole(['value' => $k, 'remainder' => '0/1'], $power-1)['value'];
				$k = $this->result($k, $k_multiplier);
				$m = $this->result($m, $k_multiplier);	
			}
			if($k_root_secondary != NULL) {
				$s = $this->result($base_root, $k_root);
			} else {
				$s = $k_root_secondary;	
			}
			$d = $this->root($m, $power);				
			$s_secondary = $s;
			$d_secondary = $d;
			if($d !== (-1)) {
				$s_division = $this->execute_divide($s, $d);
				return $s_division;	
			} else {
				$k = $k_unaltered;
				$m = $m_unaltered;
				$m_root = $m;
				$m_multiplier = $this->execute_power_whole(['value' => $m, 'remainder' => '0/1'], $power-1)['value'];
				$k = $this->result($k, $m_multiplier);
				$m = $this->result($m, $m_multiplier);	
			}
			$d = $m_root;
			$k = $this->root($k, $power);				
			
		}
		
		$subtraction_value = ['value' => $base_root, 'remainder' => '0/1'];
		
		$subtraction = $this->subtract_total($value, $subtraction_value);
		$division = $this->execute_divide($value, $subtraction_value);
		
		$division_whole_fraction = $this->whole_numerator($division);
		$division_whole_fraction_values = $this->fraction_values($division_whole_fraction);
		
		
		$subtraction_whole_fraction = $this->whole_numerator($subtraction);
		$subtraction_whole_fraction_values = $this->fraction_values($subtraction_whole_fraction);
		
		$ratio = $this->execute_divide($division_whole_fraction_values[1], $subtraction_whole_fraction_values[1])['value'];
		$subtraction_whole_fraction_values[0] = $this->result($subtraction_whole_fraction_values[0], $ratio);
		$subtraction_whole_fraction_values[1] = $this->result($subtraction_whole_fraction_values[1], $ratio);
		
		
		$f = $subtraction_whole_fraction_values[0];
		$b = $subtraction_whole_fraction_values[1];
		$k = $division_whole_fraction_values[0];
		$m = $division_whole_fraction_values[1];
		
			
		
		
		$v = $base_root;			
		$bv = $this->result($b, $v);
		$s = $this->add($bv, $f);
		$s_root = $this->root($s, $power);			
		$d = $this->root($b, $power);			
		if($d === (-1)) {
			$d = $b;
			$multiplier = $this->execute_power_whole(['value' => $b, 'remainder' => '0/1'], $power-1)['value'];
			$f = $this->result($f, $multiplier);
			$b = $this->result($b, $multiplier);
			$k = $this->result($k, $multiplier);
			$m = $this->result($m, $multiplier);
			$v = $base_root;				
			$bv = $this->result($b, $v);
			$s = $this->add($bv, $f);
			$s_root = $this->root($s, $power);			
		}
		$d_primary_alt = $d;
		$s_primary_alt = $s_root;
		
		
		
		
		if($s_root !== (-1) && $d !== (-1)) {
			$s_division = $this->execute_divide($s_root, $d);
			$root = $s_division;
			return $root;	
		} else {
			
			
			
			$k_root = $this->result($k, $base_root);
			$k_root = $this->root($k_root, $power);				
			if($k_root !== (-1)) {
				
				$s = $k_root;
				$d = $this->root($m, $power);					
				if($d !== (-1)) {
					$s_division = $this->execute_divide($s, $d);
					return $s_division;	
				}
			} 
		}
		/*if($this->disable_built_in_approximation || $object->strings->strlen($whole_values[0]) > $this->maximum_divider_exponent || $object->strings->strlen($whole_values[1]) > $this->maximum_divider_exponent) {*/
			$numerator_root = $this->root_fraction($whole_values[0], $power);
			$denominator_root = $this->root_fraction($whole_values[1], $power);
			return $this->execute_divide($numerator_root, $denominator_root);	
		/*}
		$quick_fraction = $this->quick_numeric($value);
		$approximate_value = $math->pow($quick_fraction, 1/$power); 					
		$approximate_value = $this->whole_common($approximate_value);
		return $approximate_value;*/
	}
	
	public function next_decimal_value($value) {
		$digits = $this->get_digits($value);
		while($digits[0] != 0) {
			$value = $this->add($value, '1');	
			$digits = $this->get_digits($value);
		}
		return $value;
	}
	

	public function first_digit_position($value) {
		$digits = $object->strings->str_split($value);
		$counter = 0;
		foreach($digits as $index => $digit) {
			if($digit != 0 && $digit != '.') {
				return $counter;
			}
			if($digit != '.') {
				$counter++;
			}
		}
		return $counter;
	}
	
	private function quadratic($a, $b, $c) {
		$under_root = $this->result($b, $b);
		$subtraction = $this->result_multiple([4, $a, $c]);		
		$under_root = $this->subtract($under_root, $subtraction);
		$square = $math->sqrt($under_root);
		if($object->strings->strpos($square, '.') != (-1)) {
			return NULL;
		}
		$value = $this->negative_value($b);
		$first_value = $this->add($value, $square);
		$second_value = $this->subtract($value, $square);
				
		$divider = $this->result(2, $a);
		
		$first_value = $this->execute_divide($first_value, $divider);
		$second_value = $this->execute_divide($second_value, $divider);
		return [
			$first_value,
			$second_value
		];
	}


	
	private $maximum_divider_exponent = 60;
	
	public function equals_infinity($value) {
		if($value === 'INF') {
			return true;
		}
		if($object->item_is_array($value) && $value['value'] === 'INF') {
			return true;	
		}
		return false;
	}

	public function small_divide($value, $divider) {
		$mod = $math->mod($value, $divider);

		$whole = ($value-$mod) / $divider;
		/*var_dump($mod, $whole, $value, $divider);
		var_dump($this->execute_divide($value, $divider));
		var_dump(['value' => $whole, 'remainder' => $mod.'/'.$divider]);*/
		return ['value' => $whole.'', 'remainder' => $mod.'/'.$divider];
	}


	public $primary_division;
	public $division_alt;

	/*public function _init_divide() {
		$object->log('in init divide');
		$this->primary_division = new primary_division($this);
		$object->log('in init divide 1');
		$this->division_alt = new division_alt($this);
		$object->log('in init divide 2');
	}*/	

	public function small_divide_wrap($value, $divider) {
		$digits = $object->array_reverse($this->get_digits($value));

		$results = ['value' => '0', 'remainder' => '0/1'];
		$remainder = '';
		$key = $object->count($digits)-1;
		$divider_length = $object->strings->strlen($divider);

		foreach($digits as $digit) {
			if($key >= $divider_length) {
				if($digit != 0) {
					$translation = $key-$divider_length;

					$pad = $this->pad_zeros($digit, $divider_length);
					$small_division = $this->small_divide($pad, $divider);

					if($translation >= 1) {
						$small_division = $this->multiply_total_sub($small_division, $this->pad_zeros(1, $translation));
					}

					$results = $this->add_total($results, $small_division);
				}
			} else {
				$remainder .= $digit;
			}
			$key--;
		}
		$object->log('after loop: '.$key);
		if($remainder == '') {
			$remainder = '0';
		}
		$object->log('before remainder: '.$key);
		$small_division = $this->execute_divide($remainder, $divider);
		$object->log('before add_total: '.$key);
		$results = $this->add_total($results, $small_division);
		$object->log('results');
		return $results;
	}

	public $repeat_dict = NULL;

	public function slow_divide($value, $divider) {
		$result = ['value' => '0', 'remainder' => '0/1'];

		while($this->larger($value, $divider)) {
			$result['value'] = $this->add($result['value'], 1);
			$value = $this->subtract($value, $divider);
		}
		$result['remainder'] = $value.'/'.$divider;
		return $result;
	}

	

	public function make_into_fraction_from_value($value) {
		$remainder_fraction_values = $this->fraction_values($value['remainder']);

		$numerator = $remainder_fraction_values[0];

		$numerator_addition = $this->result($value['value'], $remainder_fraction_values[1]);

		return $this->add($numerator, $numerator_addition).'/'.$remainder_fraction_values[1];
	}

	public function execute_divide($value_input, $divider_input, $shorten=false, $fast=false, $numeric=false, $pre_shorten=false, $absolute=false) {		
		$value = 1;
		$divider = 1;
		$value_remainder = NULL;
		$divider_remainder = NULL;


		if($object->item_is_array($value_input)) {
			/*$value = $value_input['value'];
			$fraction_values = $this->fraction_values($value_input['remainder']);

			$value_addition = $this->result($value, $fraction_values[1]);
			$value = $this->add($value_addition, $fraction_values[0]);*/
			$value_remainder = $this->make_into_fraction_from_value($value_input);
		} else {
			$value = $value_input;
		}

		if($object->item_is_array($divider_input)) {
			$divider_remainder = $this->make_into_fraction_from_value($divider_input);
			$fraction_values = $this->fraction_values($divider_remainder);
			$divider_remainder = $fraction_values[1].'/'.$fraction_values[0];
		} else {
			$divider = $divider_input;
		}

		if($divider_remainder === NULL) {
			$divider_remainder = '1/'.$divider;
		}
		if($value_remainder === NULL) {
			$value_remainder = $value.'/1';
		}

		/*$object->log($object->toJSON([$value_remainder, $divider_remainder]));*/

		$to_divide = $this->multiply_fraction($value_remainder, $divider_remainder);

		/*$to_divide = $this->execute_shorten_fraction($to_divide);*/

		$division_fraction_values = $this->fraction_values($to_divide);

		return $math->divide($division_fraction_values[0], $division_fraction_values[1]);


		if($divider == $value) {
			return ['value' => '1', 'remainder' => '0/1'];
		}

		/*$object->log($value);
		$object->log($divider);*/

		if($value == 0) {
			return ['value' => 0, 'remainder' => '0/1'];	
		}
		if($this->larger($divider, $value)) {
			return ['value' => '0', 'remainder' => $value.'/'.$divider];	
		}

		return $math->divide($value, $divider);
	}

	public function execute_divide_depr($value, $divider, $shorten=false, $fast=false, $numeric=false, $pre_shorten=false, $absolute=false) {
		if(!$object->item_is_array($value) && !$object->item_is_array($divider)) {
			$res = $math->divide($value, $divider);
			if($absolute) {
				return $this->absolute($res);
			}
			return $res;
		}
		/*return $this->execute_divide_sub($value, $divider, $shorten, $fast, $numeric, $pre_shorten, $absolute);*/
		/*$res = $math->divide($value, $divider);
		if($absolute) {
			return $this->absolute($res);
		}
		return $res;*/
		return $this->execute_divide_sub($value, $divider, $shorten, $fast, $numeric, $pre_shorten, $absolute);
	}
	
	public function execute_divide_depr($value, $divider, $shorten=false, $fast=false, $numeric=false, $pre_shorten=false, $absolute=false) {
		$object->log('in execute divide');
		$divide_res = $this->execute_divide_main_sub($value, $divider, $shorten, $fast, $numeric, $pre_shorten, $absolute);
		$object->log('in execute divide');
		/*$divide_res = $math->divide($value, $divider);*/
		/*$object->log($object->toJSON($res));*/
		/*$res['remainder'] = $this->minimize_fraction($res['remainder']);	*/
		if($absolute) {
			$object->log('in absolute divide');
			$divide_res = $this->absolute($divide_res);
		}
		$object->log('in divide');
		/*$res = $this->clean_remainder($res);*/
		return $divide_res;
	}

	public function execute_divide_main_sub($value, $divider, $shorten=false, $fast=false, $numeric=false, $pre_shorten=false, $absolute=false) {
		$negative = false;
		if($object->item_is_array($divider) && !$object->item_is_array($value)) {
			$value = ['value' => $value, 'remainder' => '0/1'];	
		}

		/*$result = $math->divide($value, $divider); 
		if($negative) {
			$result = $this->negative_value($result);	
		}
		$result = $this->clean_remainder($result);
		$object->log($object->toJSON(['math-divide', $value, $divider, $result]));
		return $result;	*/

		/*if(!$object->item_is_array($divider) && $object->item_is_array($value)) {
			$divider = ['value' => $divider, 'remainder' => '0/1'];	
		}*/



		/*if(!$object->item_is_array($value)) {
			$value = ['value' => $value, 'remainder' => '0/1'];	
		}*/
		/*if(!$object->item_is_array($divider)) {
			$divider = ['value' => $divider, 'remainder' => '0/1'];	
		}*/
		if($this->equals_zero($value)) {
			return ['value' => '0', 'remainder' => '0/1'];	
		}
		if($this->equals_zero($divider)) {
			return NULL;
		}
		if((($this->negative($value) && !$this->negative($divider)) || (!$this->negative($value) && $this->negative($divider))) && !$absolute) {
			$negative = true;	
		}
		
		if($value == $divider) {
			return ['value' => '1', 'remainder' => '0/1'];	
		}
		
		$value = $this->absolute($value);
		$divider = $this->absolute($divider);
		
		
		
		if(!$object->item_is_array($value) && !$object->item_is_array($divider)) {
			if($this->larger($divider, $value, false)) {
				$result = ['value' => '0', 'remainder' => $value.'/'.$divider];	
				if($negative) {
					return $this->negative_value($result);	
				}
				return $result;
			}
			if($object->strings->strlen($value) == $object->strings->strlen($divider)) {
				if($this->larger($divider, $value, false)) {
					$result = ['value' => '0', 'remainder' => $value.'/'.$divider];	
					if($negative) {
						return $this->negative_value($result);	
					}
					return $result;	
				} else {
					$counter = (-1);
					$last_subtraction;
					$subtraction = $value;
					while(!$object->isset($subtraction) || $this->larger($subtraction, 0)) {
						if($object->isset($subtraction)) {
							$last_subtraction = $subtraction;
						}
						$subtraction = $this->subtract($subtraction, $divider);
						$counter++;	
					}
					$result = ['value' => $counter, 'remainder' => $last_subtraction.'/'.$divider];	
					if($negative) {
						return $this->negative_value($result);	
					}
					return $result;
				}
			}
		}
		if($object->item_is_array($value) && $object->item_is_array($divider) && $value['value'] == 0) {
			$divider_fraction = $this->whole_numerator($divider);
			$divider_fraction = $this->flip_fraction($divider_fraction);
			
			
			$mult = $this->multiply_fraction($value['remainder'], $divider_fraction);
			
			
			$fraction_values = $this->fraction_values($mult);
			
			$fraction_values[0] = $this->absolute($fraction_values[0]);
			
			$result = $this->execute_divide($fraction_values[0], $fraction_values[1], false, false);	
			if($negative) {
				return $this->negative_value($result);	
			}
			return $result;
		}
				
		if($object->item_is_array($divider) && $divider['value'] == 0) {
			$value_fraction = '';
			if($object->item_is_array($value)) {
				$value_fraction_values = $this->fraction_values($value['remainder']);
				$numerator;
				$denominator;
				if($value_fraction_values[0] != '0') {
					$numerator = $this->add($this->result($value['value'], $value_fraction_values[1]), $value_fraction_values[0]);
					$denominator = $value_fraction_values[1];	
				} else {
					$numerator = $value['value'];
					$denominator = 1;	
				}
				$value_fraction = $numerator.'/'.$denominator;
			} else {
				$value_fraction = $value.'/1';	
			}
			$fraction_division = $this->divide_fraction($value_fraction, $divider['remainder']);
			$division_values = $this->fraction_values($fraction_division);
			return $this->execute_divide($division_values[0], $division_values[1], false, false);
		}
		
		if($object->item_is_array($divider) && $this->fraction_values($divider['remainder'])[0] != 0) {
			$fraction_values = $this->fraction_values($divider['remainder']);
			$subtraction_multiplier = $fraction_values[0];
			$value_multiplier = $fraction_values[1];
			$value_addition = $this->add($subtraction_multiplier, $this->result($divider['value'], $value_multiplier));
			$subtraction_multiplier = [
				'value' => $subtraction_multiplier,
				'remainder' => '0/1'
			];
			
			$numerator = $this->multiply_total($subtraction_multiplier, $value);
			$subtraction = $this->execute_divide($numerator, $value_addition, false, false);			
			if(!$object->item_is_array($value)) {
				$value = [
					'value' => $value,
					'remainder' => '0/1'
				];	
			}
			$value = $this->subtract_total($value, $subtraction);
			
			$divider = $divider['value'];
		} else if($object->item_is_array($divider) && $this->fraction_values($divider['remainder'])[0] == 0) {
			$divider = $divider['value'];	
		}
		
		$fraction_set;
		if($object->item_is_array($value)) {
			/*$object->log('in sub divide');
			return ['value' => '-1', 'remainder' => '0/1'];*/
			$fraction_set = $value['remainder'];
			$value = $value['value'];
			$fraction_values = $this->fraction_values($fraction_set);
			if(!$object->item_is_array($divider)) {
				$fraction_values[1] = $this->result($fraction_values[1], $divider);
			}
			$fraction_set = $fraction_values[0].'/'.$fraction_values[1];

			/*return $math->divide($fraction_values[0], $fraction_values[1]); */
		}
		
		/*baetti vid*/
		$result = $math->divide($value, $divider); 
		if($negative) {
			$result = $this->negative_value($result);	
		}
		$result = $this->clean_remainder($result);
		/*return $result;	*/

		
		/*$digits = $object->strings->str_split($value);
		$divide_value = '';
		$result = ['value' => '0', 'remainder' => '0/1'];
		
			foreach($digits as $key => $digit) {
				$divide_value = $this->pad_zeros($digit, (($digits->length-$key)-1));					
				if($divide_value != 0) {
					$division = $math->divide($divide_value, $divider); 	
					$result = $this->add_total($result, $division);		
				}
					
			}
			$result = $result['value'];
		}*/
		$remainder_result_remainder = $result['remainder'];
		$remainder_result = $this->fraction_values($remainder_result_remainder)[0];
		$result = $result['value'];
		/*$multiplication = $this->result($result, $divider);
		$remainder_result = $this->subtract($value, $multiplication);*/
		$divide_value = $remainder_result;
		
		
		
		$remainder = '0/1';
		$remainder_numeric = 0;
		if($divide_value != '0' || $object->isset($fraction_set)) {
			$remainder = $divide_value.'/'.$divider;
			if($object->isset($fraction_set)) {
				$remainder = $this->add_fraction($remainder, $fraction_set);
			}
		}
		$remainder_values = $this->fraction_values($remainder);
		if($this->larger($remainder_values[0], $remainder_values[1])) {
			
			$sub_division = $math->divide($remainder_values[0], $remainder_values[1]);
			$result = $this->add($result, $sub_division['value']);
			$remainder = $sub_division['remainder'];
		}
		$fraction_values = $this->fraction_values($remainder);
		if($fraction_values[0] == $fraction_values[1]) {
			$result = $this->add($result, 1);
			$remainder = '0/1';	
		}
		
		if($result == '') {
			$result = '0';	
		}
		if($shorten) {
			$remainder = $this->execute_shorten_fraction($remainder);
		} else {
			$remainder = $this->minimize_fraction($remainder);	
		}
		
		
		if(!$numeric) {
			$result = [
				'value' => $result,
				'remainder' => $remainder
			];
			if($negative) {
				$result = $this->negative_value($result);	
			}
			$result = $this->clean_remainder($result);
			return $result;	
		}

		return $result;
	}

	
	public function execute_divide_sub_depr($value, $divider) {
		$result = ['value' => '0', 'remainder' => '0/1'];
		
		$digits = $object->strings->str_split($value);
		
		foreach($digits as $key => $digit) {
			$divide_value = $this->pad_zeros($digit, ($object->count($digits)-$key-1));				
			if($divide_value != 0) {
				$division = $this->divide($divide_value, $divider); 					
				$result = $this->add_total($result, $division);
			}
				
		}
		$result = $result['value'];
		
		
		
		$multiplication = $this->result($result, $divider);
		$remainder_result = $this->subtract($value, $multiplication);
		$divide_value = $remainder_result;
		
		
		
		$remainder = '0/1';
		$remainder_numeric = 0;
		if($divide_value != '0' || $object->isset($fraction_set)) {
			$remainder = $divide_value.'/'.$divider;
			if($object->isset($fraction_set)) {
				$remainder = $this->add_fraction($remainder, $fraction_set);
			}
		}
		$remainder_values = $this->fraction_values($remainder);
		if($this->larger($remainder_values[0], $remainder_values[1])) {
			
			$sub_division = $this->execute_divide_sub($remainder_values[0], $remainder_values[1]);
			$result = $this->add($result, $sub_division);
		}
		return $result;	
	}
	
	public function floor($value) {
		if($this->negative($value)) {
			return $this->negative_value($this->ceil($this->absolute($value)));	
		}
		if($object->item_is_array($value)) {
			return $value['value'];	
		}
		if($object->strings->strpos($value, '.') != (-1)) {
			$split = $object->strings->explode('.', $value);
			return $split[0];	
		}
		return $value;
	}
	
	private function execute_sub_divide($value, $divider) {
		$division;
		$division = $this->sub_divide($value, $divider);
		return $division;
	}
	
	/*private function divide($value, $divider, $sub_divide_value=NULL) {		
		if($value == 0) {
			return ['value' => 0, 'remainder' => '0/1'];	
		}
		if($this->larger($divider, $value)) {
			return ['value' => '0', 'remainder' => $value.'/'.$divider];	
		}

		$object->log($value);
		$object->log($divider);

		$a_binary = $this->change_base($value, 2);
		$b_binary = $this->change_base($divider, 2);
		$object->log('a_binary');
		$object->log($a_binary);
		$object->log('b_binary');
		$object->log($b_binary);

		$modulus = $this->binary_modulus->execute($a_binary, $b_binary);

		$numerator = $modulus;

		$a_binary_value = $this->binary_subtraction($a_binary, $numerator);

		$gcd = $this->binary_modulus->gcd($a_binary_value, $b_binary);
		
		$object->log('gcd');
		$object->log($gcd);
		$gcd_value = $this->change_base($gcd, 10, 2);
		$numerator = $this->change_base($numerator, 10, 2);

		$object->log($gcd_value);
		$object->log($numerator);

		$divide_res = ['value' => $gcd_value, 'remainder' => $numerator.'/'.$divider];

		return $divide_res;
	}*/
		
	public function is_binary_power($value, $change_base=true) {
		$binary_value = $value;
		if($change_base) {
			$binary_value = $this->change_base($value, 2);
		}
		$binary_value_digits = $object->strings->str_split($binary_value);
		$one_count = 0;
		foreach($binary_value_digits as $key => $digit) {
			if($digit == '1') {
				$one_count++;	
			}
			if($one_count > 1) {
				return false;	
			}
		}
		return true;	
	}
	
	public function binary_multiplication($value, $multiplier) {
		$multiplier_digits = $this->get_digits($multiplier);
		$additions = [];
		$prefix = '';
		foreach($multiplier_digits as $key => $digit) {
			if($digit == 1) {
				$additions[] = $value.$prefix;	
			}
			$prefix .= '0';
		}
		$total = '0';
		foreach($additions as $addition) {
			$total = $this->binary_addition($total, $addition);	
		}
		return $total;
	}
	
	private function binary_multiplication_fast($value, $multiplier) {
		$result = $this->karatsuba->karatsuba($value, $multiplier);	
		return $result;
	}
	

	public function binary_addition($value, $addition) {
		$result = '';
		$value_digits = $this->get_digits($value);
		$addition_digits = $this->get_digits($addition);
		
		$value = $value_digits;
		$adder = $addition_digits;
		if($object->count($addition_digits) > $object->count($value_digits)) {
			$value = $addition_digits;
			$adder = $value_digits;	
		}
		$carry_bit = '0';
		foreach($value as $key => $digit) {
			$value_add = '0';
			/*if($object->isset($adder[$key])) {*/
			if($adder->length > $key) {
				if($digit == '1' && $adder[$key] == '0') {
					if($carry_bit == '0') {
						$value_add = '1';
					} else {
						$carry_bit = '1';
						$value_add = '0';	
					}
				} else if($digit == '1' && $adder[$key] == '1') {
					if($carry_bit == '0') {
						$value_add = '0';
						$carry_bit = '1';
					} else {
						$value_add = '1';
						$carry_bit = '1';
					}	
				} else if($digit == '0' && $adder[$key] == '1') {
					if($carry_bit == '1') {
						$value_add = '0';
						$carry_bit = '1';	
					} else {
						$value_add = '1';	
					}
				} else {
					if($carry_bit == '1') {
						$value_add = '1';	
						$carry_bit = '0';
					} else {
						$value_add = '0';
					}
				}
			} else {
				if($carry_bit == '1' && $digit == '1') {
					$value_add = '0';
					$carry_bit = '1';
				} else if($digit == '0' && $carry_bit == '1') {
					$value_add = '1';
					$carry_bit = '0';	
				} else {
					$value_add = $digit;
				}
			}
			$result .= $value_add;
		}
		if($carry_bit == '1') {
			$result .= $carry_bit;	
		}
		return $object->strings->strrev($result);
	}
	
	public function binary_subtraction($value, $subtraction) {
		$value_digits = $this->get_digits($value);
		$addition_digits = $this->get_digits($subtraction);
		$negative = false;
		$value = $value_digits;
		$adder = $addition_digits;
		$carry_bit = '0';
		foreach($value as $key => $digit) {
			if($object->isset($adder[$key])) {
				if($adder[$key] == 1 && $digit == 1) {
					$value[$key] = '0';	
				} else if($adder[$key] == 1 && $digit == 0) {
					$value[$key] = '-1';	
				}
			}
		}
		$value = $this->invert_negatives($object->reverse($value));
		$result = $object->strings->implode('', $value);
		return $result;
	}
	

	private function invert_negatives($value) {
		$last_one = (-1);
		$key = 0;
		foreach($value as $key => $digit) {
			$digit = $value[$key];
			if($digit == 1) {
				$last_one = $key;	
			}
			if($digit == '-1' && $last_one != (-1)) {
				$value[$last_one] = 0;
				$counter = $last_one+1;
				while($counter <= $key) {
					$value[$counter] = 1;
					$last_one = $counter;	
					$counter++;
				}
			}
			$key++;
		}
		return $value;
	}
	
	private $normalize_divider_depth = 0;

		
	private $max_divider_length = 2;	
		
	public function items_exist($array, $values) {
		$all_found = true;
		foreach($values as $value) {
			$found = false;
			foreach($array as $array_item) {
				if($array_item == $value) {
					$found = true;	
				}
			}
			if(!$found) {
				$all_found = false;	
			}
		}
		return $all_found;
	}
			
	public function integer_fraction($value) {
		$fraction = $this->fraction_values($value);
		if($fraction[0] > $fraction[1]) {
			$division = $this->execute_divide($fraction[0], $fraction[1]);
			$integer = $division['value'];
			$whole_numerator = $this->result($integer, $fraction[1]);			
			$remaining_numerator = $this->subtract($fraction[0], $whole_numerator);			
			return [
				$integer,
				$remaining_numerator.'/'.$fraction[1]
			];	
		} else {
			return [
				0,
				$value
			];	
		}
	}

	public function numeric_value($exponent_pair) {
		if($exponent_pair['exponent'] == 0) {
			return $exponent_pair['value'];	
		}
		$exponent_pair_list = [];
		if($object->isset($exponent_pair['exponent'])) {
			$exponent_pair_list[] = $exponent_pair;
		} else {
			$exponent_pair_list = $exponent_pair;	
		}
		$result;
		foreach($exponent_pair_list as $exponent_pair) {
			$value = $exponent_pair['value'];
			$counter = 0;
			$zeros = '';
			if($object->strings->strpos($value, '.') == (-1) && $exponent_pair['exponent'] >= 0) {
				while($counter < $exponent_pair['exponent']) {
					$zeros .= '0';
					$counter++;	
				}
				$value .= $zeros;
			} else {
				$prefix = false;
				if($exponent_pair['exponent'] < 0) {
					$prefix = true;	
				}
				$value = $this->place_decimal($value, $exponent_pair['exponent'], true, $prefix);
			}
			
			$value = $this->clean_fraction($value);
			if(!$object->isset($result)) {
				$result = $value;	
			} else {
				$result = $this->add($result, $value);	
			}
		}
		return $result;
	}

	public function real_fraction($value, $decimal_points=10, $level=0) {
		$negative = '';
		if($this->negative($value)) {
			$negative = '-';	
		}
		$value = $this->absolute($value);
		$fraction_values = $this->fraction_values($value);
		if($fraction_values[0] != '0') {
			$whole = $this->integer_fraction($value);
			$result = $object->strings->substr($this->calculate_real_fraction($whole[1], $decimal_points), 0, $decimal_points);
			return $negative.$this->numeric_whole($whole[0], $this->place_decimal_alt($result, $decimal_points));
		}
		return '0';
	}
	
	public function quick_numeric($value, $decimal_places=10) {
		return $this->numeric_whole($value['value'], $value['remainder'], $decimal_places);
	}
	
	public function numeric_whole($value, $fraction, $decimal_places=10) {
		$negative = false;
		if($object->strings->strpos($value, '-') != (-1)) {
			$negative = true;	
		}
		if($object->strings->strpos($fraction, '-') != (-1)) {
			$negative = true;	
		}
		$value = $this->absolute($value);
		$fraction = $this->absolute($fraction);
		if($object->strings->strpos($fraction, '/') != (-1)) {
			$fraction = $this->real_fraction($fraction, $decimal_places);
			$fraction = $this->absolute($fraction);
		}
		
		if($object->strings->strpos($fraction, '.') == (-1)) {
			$result = $value;	
		} else {
			$fraction_value = $object->strings->explode('.', $fraction);
			$addition = $this->add($this->absolute($value), $fraction_value[0]);
			$result = $addition.'.'.$fraction_value[1];
		}
		if($negative) {
			$result = '-'.$result;	
		}
		return $result;	
	}

	private function clean_remainder($value) {
		$fraction_values = $this->fraction_values($value['remainder']);
		$numerator_clean = $this->remove_leading_zeros($this->remove_minus($fraction_values[0]));
		if($numerator_clean == '0') {
			$value['remainder'] = '0/1';	
		} else {
			$fraction_values = $this->fraction_values($value['remainder']);
			$numerator = $this->remove_leading_zeros($fraction_values[0]);
			$denominator = $this->remove_leading_zeros($fraction_values[1]);
			$value['remainder'] = $numerator.'/'.$denominator;
		}
		return $value;
	}


	private function clean_fraction($value) {
		$clean = true;
		if($object->strings->strpos($value, '.') != (-1)) {
			$split = $object->strings->explode('.', $value);
			$fraction = $object->strings->str_split($split[1]);
			foreach($fraction as $digit) {
				if($digit != 0) {
					$clean = false;	
				}
			}
			if($clean) {
				return $split[0];
			}
		}
		return $value;
	}
	
	public function add_sub($term_a, $term_b, $base=10, $limit_decimals=false) {
		$term_a = $this->absolute($term_a);
		$term_b = $this->absolute($term_b);
		$decimal_point = (-1);
		if($object->strings->strpos($term_a, '.') != (-1) || $object->strings->strpos($term_b, '.') != (-1)) {
			$terms = $this->synchronize_values($term_a, $term_b);
			$term_a = $terms['a'];
			$term_b = $terms['b'];
			$decimal_point = $terms['fraction_length'];
		}
		$a_digits = $this->get_digits($term_a);
		$b_digits = $this->get_digits($term_b);
		if($object->count($b_digits) > $object->count($a_digits)) {
			$switch = $a_digits;
			$a_digits = $b_digits;
			$b_digits = $switch;	
		}
		$return_digits = [];
		$carry_value = NULL;
		foreach($a_digits as $key_a => $a_digit) {
			$addition;
			/*if($object->isset($b_digits[$key_a])) {*/
			if($b_digits->length > $key_a) {
				if($a_digit == '') {
					$a_digit = 0;	
				}
				$b_digit = $b_digits[$key_a];
				if($b_digit == '') {
					$b_digit = 0;	
				}
				$addition = $a_digit + $b_digit;
			} else {			
				$addition = $a_digit;	
			}
			if($carry_value != NULL && $carry_value != '') {
				$addition += $carry_value;
				$carry_value = NULL;	
			}
			if(!$limit_decimals) {
				if((($addition >= 10 && $key_a > 0) || ($base == 10 && $addition >= 10))) {
					$addition_digits = $object->strings->str_split($addition);
					$carry_value = $addition_digits[0];
					$addition = $addition_digits[1];	
				} else if($addition >= $base && ($key_a == 0)) {
					$carry_value = 1;
					$addition = $addition - $base;
				}
			} else {
				if($addition >= $base) {
					$carry_value = 1;
					$addition = $addition - $base;
				}
			}
			$return_digits[] = $addition;
		}
		if($carry_value != NULL) {
			$return_digits[] = $carry_value;	
		}
		$return_digits = $object->reverse($return_digits);
		$value = $object->strings->implode('', $return_digits);
		if($decimal_point != (-1)) {
			$digits = $this->get_digits($value);
			$value = '';
			foreach($digits as $key => $digit) {
				if($key == $decimal_point) {
					$value = $digit.'.'.$value;	
				} else {
					$value = $digit.$value;	
				}
			}
		}
		if($limit_decimals) {
			$re_add = false;
			$digits = $this->get_digits($value);
			foreach($digits as $key => $digit) {
				if($digit >= $base) {
					$re_add = true;	
				}
			}
			if($re_add) {
				$value = $this->add_sub($value, '0', $base, true);	
			}
		}
		return $value;
	}

	public function add_place($term_a, $term_b, $place, $base=10, $limit_decimals=false) {
		$split_place = $object->strings->strlen($term_a) - $place;
		$term_a_remainder = $object->strings->substr($term_a, $split_place, $object->strings->strlen($term_a));
		$term_a_add = $object->strings->substr($term_a, 0, $split_place);
		$addition = $this->add($term_a_add, $term_b, $base, $limit_decimals);
		$result = $addition.$term_a_remainder;
		return $result;
	}
	
	
	public function synchronize_values($term_a, $term_b) {
		$fraction_length = 0;
		$a_split = $object->strings->explode('.', $term_a);
		$b_split = $object->strings->explode('.', $term_b);
		if($object->isset($a_split[1])) {
			$fraction_length = $object->strings->strlen($a_split[1]);	
		}
		if($object->isset($b_split[1]) && $object->strings->strlen($b_split[1]) > $fraction_length) {
			$fraction_length = $object->strings->strlen($b_split[1]);	
		}
		$diff = $fraction_length;
		if(!$object->isset($a_split[1])) {
			$a_split[1] = '';
		}
		$diff = $fraction_length - $object->strings->strlen($a_split[1]);
		$counter = 0;
		while($counter < $diff) {
			$a_split[1] .= '0';	
			$counter++;
		}
		if(!$object->isset($b_split[1])) {
			$b_split[1] = '';
		}
		$diff = $fraction_length - $object->strings->strlen($b_split[1]);
		$counter = 0;
		while($counter < $diff) {
			$b_split[1] .= '0';	
			$counter++;
		}
		
		$term_a = $object->strings->implode('.', $a_split);
		$term_b = $object->strings->implode('.', $b_split);
		$result = [
			'a' => $term_a,
			'b' => $term_b,
			'fraction_length' => $fraction_length
		];
		return $result;
	}

	
	/*public function calculate_real_fraction($value, $decimal_points) {
		if($decimal_points <= 0) {
			return '';	
		}
		$fraction_values = $this->fraction_values($value);
		
		$division = $this->execute_divide($this->add_zeros('1', $object->strings->strlen($fraction_values[1])), $fraction_values[1]);
		$numerator = $this->multiply_total(['value' => $fraction_values[0], 'remainder' => '0/1'], $division);
		$denominator = $this->multiply_total(['value' => $fraction_values[1], 'remainder' => '0/1'], $division);
		$result = $numerator['value'];
		$result = $this->pad_zeros($result, $object->strings->strlen($fraction_values[1])-$object->strings->strlen($result), true);
		return $result.$this->calculate_real_fraction($numerator['remainder'], $decimal_points-$object->strings->strlen($result));
	}*/

	/*public function calculate_real_fraction($value, $decimal_points) {
		if($decimal_points <= 0) {
			return '';	
		}
		$fraction_values = $this->fraction_values($value);
		
		$division = $this->execute_divide($this->add_zeros('1', $object->strings->strlen($fraction_values[1])), $fraction_values[1]);
		$numerator = $this->multiply_total(['value' => $fraction_values[0], 'remainder' => '0/1'], $division);
		$denominator = $this->multiply_total(['value' => $fraction_values[1], 'remainder' => '0/1'], $division);
		$result = $numerator['value'];
		$result = $this->pad_zeros($result, ($object->strings->strlen($fraction_values[1])-$object->strings->strlen($result)), true);
		return $result.$this->calculate_real_fraction($numerator['remainder'], ($decimal_points-$object->strings->strlen($result)));
	}*/

	public function calculate_real_fraction($value, $decimal_points) {
		$queue = new runtime_queue();

		$evaluation = $this;

		$original_value = $value;

		$callback = function($state) {
			$value = $state['next_input']['value'];
			$decimal_points = $state['next_input']['decimal_points'];
			if($decimal_points <= 0) {
				$state['terminating'] = true;
				return false;	
			}
			$fraction_values = $evaluation->fraction_values($value);

			$division = $evaluation->execute_divide($evaluation->add_zeros('1', $object->strings->strlen($fraction_values[1])), $fraction_values[1]);
			$numerator = $evaluation->multiply_total(['value' => $fraction_values[0], 'remainder' => '0/1'], $division);
			$denominator = $evaluation->multiply_total(['value' => $fraction_values[1], 'remainder' => '0/1'], $division);
			$result = $numerator['value'];
			$result = $evaluation->pad_zeros($result, $object->strings->strlen($fraction_values[1])-$object->strings->strlen($result), true);

			/*if($division['remainder'] == $original_value) {
				$object->log('same');
				while($decimal_points > 0) {
					$result = $state['output_value'].$result;
					$result_length = $object->strings->strlen($result);
					$decimal_points = $decimal_points - $result_length;
				}
				$state['terminating'] = true;
				$state['output_value'] .= $result;
				return false;
			}*/

			$state['output_value'] .= $result;
			$state['next_input']['value'] = $numerator['remainder'];
			$state['next_input']['decimal_points'] = $decimal_points - $object->strings->strlen($result);
		};

		$queue->set_callback($callback);

		$state = $queue->run([
			'value' => $value,
			'decimal_points' => $decimal_points
		], '');

		return $state['output_value'];
	}

	/*public function calculate_real_fraction_depr($value, $decimal_points) {
		if($decimal_points <= 0) {
			return '';	
		}
		$fraction_values = $this->fraction_values($value);

		$division = $this->execute_divide($this->add_zeros('1', $object->strings->strlen($fraction_values[1])), $fraction_values[1]);
		$numerator = $this->multiply_total(['value' => $fraction_values[0], 'remainder' => '0/1'], $division);
		$denominator = $this->multiply_total(['value' => $fraction_values[1], 'remainder' => '0/1'], $division);
		$result = $numerator['value'];
		$result = $this->pad_zeros($result, $object->strings->strlen($fraction_values[1])-$object->strings->strlen($result), true);

		if($division['remainder'] == $value) {
			while($decimal_points > 0) {
				$result .= $numerator['value'];
				$decimal_points--;
			}
			return $result;
		}

		$result = $result.$this->calculate_real_fraction($numerator['remainder'], ($decimal_points-$object->strings->strlen($result)));
		return $result;
	}*/
	
	
	public function divide_fraction($value_a, $value_b, $shorten=false) {
		$fraction_a = $this->fraction_values($value_a);
		$fraction_b = $this->fraction_values($value_b);
		
		$result = $this->result($fraction_a[0], $fraction_b[1]).'/'.$this->result($fraction_a[1], $fraction_b[0]);
		if($shorten) {
			$this->execute_shorten_fraction($shorten);	
		}
		return $result;	
	}
	
	public function subtract_fraction($value_a, $value_b) {
		
		$common = $this->common_denominator($value_a, $value_b);
		$fraction_a = $this->fraction_values($common[0]);
		$fraction_b = $this->fraction_values($common[1]);
		$numerator = $this->subtract($fraction_a[0], $fraction_b[0]);		
		$denominator = $fraction_a[1];
		return $numerator.'/'.$denominator;
	}
	
	public function subtract_total($value_a, $value_b, $shorten=false) {
		return $math->subtract_total($value_a, $value_b);
		$result;
		if($this->negative($value_a) && $this->negative($value_b)) {				
			$result = $this->subtract_total_sub($this->absolute($value_b), $this->absolute($value_a));
		} else if($this->negative($value_a) && !$this->negative($value_b)) {				
			$result = $this->negative_value($this->add_total_sub($this->absolute($value_b), $this->absolute($value_a)));
		} else if(!$this->negative($value_a) && $this->negative($value_b)) { 			
			$result = $this->add_total_sub($this->absolute($value_a), $this->absolute($value_b));
		} else {
			$result = $this->subtract_total_sub($value_a, $value_b);	
		}
		
		if($shorten) {
			$result['remainder'] = $this->execute_shorten_fraction($result['remainder']);	
		}
		$result = $this->clean_remainder($result);
		return $result;
	}
	
	private function subtract_total_sub($value_a, $value_b) {
		$fraction_a = $this->fraction_values($value_a['remainder']);
		$fraction_b = $this->fraction_values($value_b['remainder']);
		$fraction_a[0] = $this->add($fraction_a[0], $this->result($fraction_a[1], $value_a['value']));
		$fraction_b[0] = $this->add($fraction_b[0], $this->result($fraction_b[1], $value_b['value']));
		$result = $this->subtract_fraction($this->make_fraction($fraction_a), $this->make_fraction($fraction_b));
		$fraction = $this->fraction_values($result);
		$negative = false;
		if($this->negative($fraction[0])) {
			$negative = true;	
		}
		$result = $this->execute_divide($this->absolute($fraction[0]), $fraction[1]);
		if($negative) {
			return $this->negative_value($result);	
		}
		return $result;
	}
	
	public function make_fraction($fraction) {
		return $fraction[0].'/'.$fraction[1];
	}
	
	public function add_fraction($value_a, $value_b) {
		$fraction_a = $this->fraction_values($value_a);
		$fraction_b = $this->fraction_values($value_b);
		
		if($fraction_a[0] == 0) {
			return $value_b;	
		}
		if($fraction_b[0] == 0) {
			return $value_a;	
		}
		if($fraction_a[1] != $fraction_b[1]) {
			$common = $this->common_denominator($value_a, $value_b);
			$fraction_a = $this->fraction_values($common[0]);
			$fraction_b = $this->fraction_values($common[1]);
		}
		$numerator = $this->add($fraction_a[0], $fraction_b[0]);		
		$denominator = $fraction_a[1];
		return $numerator.'/'.$denominator;
	}
	
	public function make_fraction_negative($fraction_value) {
		$fraction_values = $this->fraction_values($fraction_value);
		$fraction_values[0] = $this->negative_value($fraction_values[0]);
		return $fraction_values[0].'/'.$fraction_values[1];	
	}
	
	public function add_total($term_a, $term_b, $shorten=false) {
		return $math->add_total($term_a, $term_b);
		$result;
		if($this->negative($term_a) && $this->negative($term_b)) {
			$result = $this->negative_value($this->add_total_sub($this->absolute($term_a), $this->absolute($term_b)));	
		} else if($this->negative($term_a) && !$this->negative($term_b)) {
			$result = $this->subtract_total($this->absolute($term_b), $this->absolute($term_a));
		} else if(!$this->negative($term_a) && $this->negative($term_b)) {
			$result = $this->subtract_total($this->absolute($term_a), $this->absolute($term_b));
		} else {
			$result = $this->add_total_sub($term_a, $term_b);	
		}
		$remainder_values = $this->fraction_values($result['remainder']);
		if($remainder_values[0] == $remainder_values[1]) {
			$result['value'] = $this->add($result['value'], 1);	
			$result['remainder'] = '0/1';
		} else if($this->larger($remainder_values[0], $remainder_values[1])) {
			$division = $this->execute_divide($remainder_values[0], $remainder_values[1]);
			$result['value'] = $this->add($result['value'], $division['value']);
			$result['remainder'] = $division['remainder'];	
		}
		if($shorten) {
			$result['remainder'] = $this->execute_shorten_fraction($result['remainder']);	
		}	
		$result = $this->clean_remainder($result);
		return $result;
	}
	
	public $debug_depth = 0;

	private function add_total_sub($value_a, $value_b, $shorten=false) {		
		$addition = $this->add($value_a['value'], $value_b['value']);
		$value_a_negative = false;
		$value_b_negative = false;

		$fraction = $this->add_fraction($value_a['remainder'], $value_b['remainder']);
		$fraction_values = $this->fraction_values($fraction);
		$fraction_negative = false;
		
		
		$division['value'] = 0;
		if($this->larger($fraction_values[0], $fraction_values[1])) {
			$subtraction = $this->subtract($fraction_values[0], $fraction_values[1]);
			$division['value'] = 1;
			$division['remainder'] = $subtraction.'/'.$fraction_values[1];
		}
		
		if($division['value'] > 0) {
			$addition = $this->add($addition, $division['value']);
			$fraction = $division['remainder'];
			if($shorten) {
				$fraction = $this->execute_shorten_fraction($division['remainder']);
			}
		}
		
		return [
			'value' => $addition,
			'remainder' => $fraction
		];
	}
	
	public function exponent($value) {
		$digits = $object->strings->str_split($value);
		$is_exponent = true;
		if($digits[0] != 1) {
			$is_exponent = false;	
		}
		$counter = 1;
		while($counter < $object->count($digits)) {
			if($digits[$counter] != 0) {
				$is_exponent = false;	
			}
			$counter++;	
		}
		return $is_exponent;
	}
	
	public function count_trailing_zeros($value) {
		$digits = $this->get_digits($value);
		$counter = 0;
		$zero_count = 0;
		$break = false;
		while($counter < $object->count($digits)) {
			if($digits[$counter] == 0 && !$break) {
				$zero_count++;
			} else {
				$break = true;	
			}
			$counter++;	
		}
		return $zero_count;
	}
	
	public function unit_translation($value, $measure) {
		if($this->larger($this->absolute($measure), 1)) {
			$translation = '1/'.$measure;
		} else {
			$translation = $measure.'/1';
		}	
		return $translation;
	}
	
	public function make_into_fraction($a, $b) {
		$multiplier_value;
		if($this->fraction_values($a['remainder'])[1] == $this->fraction_values($b['remainder'])[1]) {
			$multiplier_value = $this->fraction_values($a['remainder'])[1];
		} else {				
			$multiplier_value = $this->result($this->fraction_values($a['remainder'])[1], $this->fraction_values($b['remainder'])[1]);	
		}
		$multiplier = ['value' => $multiplier_value, 'remainder' => '0/1'];
		$a_multiplication = $this->multiply_total($a, $multiplier);
		$b_multiplication = $this->multiply_total($b, $multiplier);
		$fraction = $a_multiplication['value'].'/'.$b_multiplication['value'];	
		return $fraction;
	}
	
	public function equals_zero($value) {
		if(!$object->item_is_array($value)) {
			if($value == 0) {
				return true;	
			}
		} else {
			if($value['value'] == 0 && $this->fraction_values($value['remainder'])[0] == 0) {
				return true;	
			}
		}
		return false;
	}
	
	public function negative($value) {
		if($object->item_is_array($value)) {
			if($object->isset($value['negative']) && $value['negative']) {
				return true;	
			}
			if($object->strings->strpos($value['value'], '-') != (-1) || ($value['value'] == 0 && $object->strings->strpos($value['remainder'], '-') != (-1))) {
				return true;
			}
			return false;
		}
		if($object->strings->strpos($value, '-') != (-1)) {
			return true;	
		}
		return false;
	}
	
	public function negative_value($value) {
		if($object->item_is_array($value) && $object->isset($value['value'])) {
			$value = [...$value];
			
			$fraction_values = $this->fraction_values($value['remainder']);
			if($value['value'] != 0) {
				$value['value'] = $this->negative_value($value['value']);	
			} else if($fraction_values[0] != 0) {
				$value['remainder'] = $this->negative_value($fraction_values[0]).'/'.$fraction_values[1];
			}
			return $value;
		}
		if($object->strings->strpos($value, '-') == (-1)) {
			return '-'.$value;
		} else {
			$split = $object->strings->explode('-', $value);
			return $split[1];
		}
	}
	
	public function absolute($value) {
		if($object->item_is_array($value) && $object->isset($value['value'])) {
			return [
				'value' => $this->absolute($value['value']),
				'remainder' => $this->absolute($value['remainder'])
			];
		}
		if($object->strings->strpos($value, '-') != (-1)) {
			$split = $object->strings->explode('-', $value);
			if($split->length > 1) {
				return $split[1];	
			}
		}
		return $value;
	}
	
	public function larger($value_a, $value_b, $equal=true) {
		$larger = true;
		/*if(!$object->isset($value_a)) {
			$value_a = 0;
		}
		if(!$object->isset($value_b)) {
			$value_b = 0;
		}*/
		
		$value_a = $this->remove_leading_zeros($value_a);
		$value_b = $this->remove_leading_zeros($value_b);
		
		if($this->negative($value_a) && !$this->negative($value_b)) {
			return false;	
		} else if(!$this->negative($value_a) && $this->negative($value_b)) {
			return true;	
		} else if($this->negative($value_a) && $this->negative($value_b)) {
			return $this->larger($this->absolute($value_b), $this->absolute($value_a), $equal);	
		}
		if(!$equal) {
			if($value_a == $value_b) {
				return false;	
			}
		}
		if($object->strings->strlen($value_a) < $object->strings->strlen($value_b)) {
			$larger = false;	
		} else if($object->strings->strlen($value_a) == $object->strings->strlen($value_b)) {
			$digits_a = $object->strings->str_split($value_a);
			$digits_b = $object->strings->str_split($value_b);
			$counter = 0;
			$break = false;
			while($counter < $object->count($digits_a) && $larger && !$break) {
				if($digits_a[$counter] < $digits_b[$counter]) {
					$larger = false;	
				} else if($digits_a[$counter] > $digits_b[$counter]) {	
					$break = true;
				}
				$counter++;
			}
		}
		
		return $larger;
	}

	public function larger_total($value_a, $value_b, $same=true) {
		if($this->larger($value_a['value'], $value_b['value'], false)) {
			return true;	
		} else if($value_a['value'] == $value_b['value']) {
			
			$common = $this->common_denominator($value_a['remainder'], $value_b['remainder']);
			$fraction_a = $this->fraction_values($common[0]);
			$fraction_b = $this->fraction_values($common[1]);
			if($this->larger($fraction_a[0], $fraction_b[0], $same)) {
				return true;	
			}
		}
		return false;
	}
	
	public function larger_fraction($value_a, $value_b) {
		$common = $this->common_denominator($value_a, $value_b);
		$fraction_a = $this->fraction_values($common[0]);
		$fraction_b = $this->fraction_values($common[1]);
		if($this->larger($fraction_a[0], $fraction_b[0])) {
			return true;	
		}
		return false;	
	}
	
	public function even($value) {
		if($value == 0) {
			return true;	
		}
		$digits = $this->get_digits($value);
		$even = true;
		
		if($math->mod($digits[0], 2) == 0) {
			return true;	
		}
		return false;
	}

	
	public function even_alt($value) {
		$binary_value = $this->change_base($value, 2);
		$digits = $this->get_digits($binary_value);
		if($digits[0] == 0) {
			return true;	
		}
		return false;
	}
		
	public function multiple($value, $multiple) {
		$digits = $this->get_digits($value);
		$is_multiple = true;
		foreach($digits as $key => $digit) {
			if($math->mod($digit, $multiple) != 0) {
				$is_multiple = false;	
			}
		}
		return $is_multiple;
	}


	private function add_arr($arr, $value) {
		if(!$object->item_is_array($value)) {
			$value = [$value];
		} 
		foreach($value as $val) {
			if(!$object->in_array($val, $arr)) {
				$arr[] = $val;	
			}
		}	
	}
	
	private $divisible_values_result;
	private $current_divisible_value = [];
	
	
	private $prime_factors;
	
	private $last_factor_value = 2;

	public function prime_factors_new($value) {
		$prime_factors_alt_instance = new prime_factors_alt2($value, $this);

		$factors = $prime_factors_alt_instance->get();
		return $factors;
	}

	public function prime_factors_alt($value) {
		if($value == 0) {
			return [];	
		}
		$prime_factorization = new prime_factorization($this);
		return $prime_factorization->factor($value);	
	}
	
	/*public function reduce_common_factors($fraction) {
		$fraction_values = $this->fraction_values($fraction);
		$numerator = $this->prime_factors($fraction_values[0]);
		$denominator = $this->prime_factors($fraction_values[1]);
		
		
		$numerator_result = $numerator;
		foreach($numerator as $key_numerator => $prime_factor) {
			$key = $object->index_of($prime_factor, $denominator);
			if($key != (-1)) {
				delete ($denominator[$key]);	
				delete ($numerator_result[$key_numerator]);
			}
		}
		$numerator = $numerator_result;
		
		
		
		$numerator_result = 1;
		foreach($numerator as $value) {
			$numerator_result = $this->result($numerator_result, $value);	
		}
		$denominator_result = 1;
		foreach($denominator as $value) {
			$denominator_result = $this->result($denominator_result, $value);	
		}
		
		
		return $numerator_result.'/'.$denominator_result;	
	}*/


	public function list_divisors($value) {
		$factors = $this->prime_factors($value);
		$divisors = $factors;
		
		$combinations = $this->combinations($factors);
		
		foreach($combinations as $combination) {
			$result = 1;
			foreach($combination as $combination_value) {
				$result = $this->result($result, $combination_value);
			}
			if(!$object->in_array($result, $divisors)) {
				$divisors[] = $result;
			}
		}
		$divisors[] = 1;
		$divisors = $object->array_unique($divisors);
		return $divisors;
	}



	public function string_sum($value, $alter=false, $subtract=false) {
		$digits = $this->get_digits($value);
		$interlope = 0;
		$sum = 0;
		foreach($digits as $digit) {
			if(!$alter) {
				if($interlope == 0) {
					if(!$subtract) {
						$sum = $this->add($sum, $digit);	
					} else {
						$sum = $this->subtract($sum, $digit);		
					}
				}
			} else {
				if($interlope == 1) {
					if(!$subtract) {
						$sum = $this->add($sum, $digit);	
					} else {
						$sum = $this->subtract($sum, $digit);
					}
				}
			}
			if($interlope == 0) {
				$interlope = 1;	
			} else {
				$interlope = 0;	
			}
		}
		return $sum;
	}
		
	public function verified_divisible($value, $divider) {
		$division = $this->execute_divide($value, $divider);
		$numerator = $this->fraction_values($division['remainder'])[0];
		if($numerator == 0) {
			return true;	
		}
		return false;
	}
	
	
	public function digit_sum($value, $subtract=false) {
		$digits = $this->get_digits($value);
		$sum = 0;
		foreach($digits as $digit) {
			if(!$subtract) {
				$sum += $digit;	
			} else {
				$sum = $digit - $sum;	
			}
			
		}
		return $this->absolute($sum);
	}
	
	public function final_digit_sum($value) {
		while($object->strings->strlen($value) > 1) {
			$value = $this->digit_sum($value);	
		}
		return $value;
	}
	
	public function normalize_base($value, $base) {
		$digits = $this->get_digits($value);
		$exponent_count = $object->count($digits);
		$result = '';
		foreach($digits as $exponent => $digit) {
			if($exponent == 0) {
				$result = $this->add($result, $digit);
			} else {
				$digit = $digit*$base;
				$result = $this->add_place($result, $digit, $exponent-1);
			}
		}
		return $result;
	}
	
	public function add_zeros($value, $count) {
		$counter = 0;
		while($counter < $count) {
			$value .= '0';
			$counter++;	
		}
		return $value;
	}
	

	public function bit_shift($value, $places, $change_base=true) {
		/*return $math->bit_shift($value, $places, $change_base);*/
		$binary_value;
		if($change_base) {
			$binary_value = $this->change_base($value, 2);
		} else {
			$binary_value = $value;	
		}
		$binary_value = $this->pad_zeros($binary_value, $places);
		
		$resutling_value;
		if($change_base) {
			$resulting_value = $this->change_base_decimal($binary_value, 2);
		} else {
			$resulting_value = $binary_value;	
		}
		return $resulting_value;	
	}
	
	public function bit_shift_right($value, $places, $change_base=true) {
		/*return $math->bit_shift_right($value, $places, $change_base);*/
		$binary_value;
		if($change_base) {
			$binary_value = $this->change_base($value, 2);
		} else {
			$binary_value = $value;	
		}
		$places = $this->subtract($object->strings->strlen($binary_value), $places);
		$places = $this->subtract($places, '1');
		$binary_value = $object->strings->substr($binary_value, 0, $places);
		$resutling_value;
		if($change_base) {
			$resulting_value = $this->change_base_decimal($binary_value, 2);
		} else {
			$resulting_value = $binary_value;	
		}
		return $resulting_value;	
	}
	
	public function change_base_decimal($value, $old_base) {
		$new_value = '0';
		$digits = $this->get_digits($value);
		$exponent_value = 1;
		foreach($digits as $index => $digit) {
			$value_addition = $this->result($digit, $exponent_value);
			$new_value = $this->add($new_value, $value_addition);
			$exponent_value = $this->result($exponent_value, $old_base);	
		}
		return $new_value;
	}
	
	public function change_base_total($value, $new_base, $base=10, $limit_decimals=false) {
		if($object->item_is_array($new_base)) {
			$new_base = $new_base['value'];	
		}
		$value['value'] = $this->change_base($value['value'], $new_base, $base, $limit_decimals);
		$value['remainder'] = $this->fraction_base($value['remainder'], $new_base, $base, $limit_decimals);	
		return $value;
	}
	
	private $maximum_base_change_exponent;


	public function change_base($value, $new_base, $base=10, $limit_decimals=true, $find_last_exponent=true) {
		/*return $math->change_base($value, $new_base, $base, $limit_decimals, $find_last_exponent);*/
		$unaltered_value = $value;
		if($new_base == 10) {
			return $this->change_base_decimal($value, $base);	
		}
		if($new_base > 10 || $new_base < 2) {
			return false;	
		}
		if($value == 0) {
			return '0';	
		}
		if($value == 1) {
			return '1';	
		}
		if($base == '10') {
			$number_conversion = new number_conversion($this);
			return $number_conversion->convert($value, $new_base);
		}
		$digits = $object->strings->str_split($value);		
		$exponent_count = $object->count($digits)-1;
		$result = 0;
		$carry_bit = 0;
		$exponent_values = [];
		$exponent_value = $new_base;
		$exponent_values[] = 1;
		$exponent_values[] = $exponent_value;
		$counter = 0;
		while($counter < ($object->count($digits)-2)) {
			$exponent_value = $this->result($exponent_value, $new_base);
			$exponent_values[] = $exponent_value;
			$counter++;
		}
		$exponent_values = $object->reverse($exponent_values);
		$this->maximum_base_change_exponent = $exponent_values[0];
		$result = '';
		
		$exponent = 0;
		$updated_value = $value;
		while($exponent <= $exponent_count) {
			$digit = $digits[$exponent]; 
			$exponent_length = $exponent_count - $exponent;
			if($exponent == ($object->count($digits)-1)) {
				$result = $this->add_sub($result, $digit, $new_base, $limit_decimals); 			
			} else {
				$digit_value = $digit;
				$counter = 0;
				while($counter < $exponent_length) {
					$digit_value .= '0';	
					$counter++;
				}
				$division_value = $object->strings->implode('', $digits);
				$division = $this->execute_divide($division_value, $exponent_values[$exponent]);
				$new_digit = $division['value'];
				$subtract_value = $this->result($new_digit, $exponent_values[$exponent]);
				$remainder = $this->subtract($updated_value, $subtract_value);
				
				$updated_value = $remainder;				
				$count_difference = $exponent_count+1-($object->strings->strlen($updated_value));
				$updated_value = $this->pad_zeros($updated_value, $count_difference, true);
				$digits = $object->strings->str_split($updated_value);
				
				$digit_prefix = '';
				if($new_digit > $new_base) {
					$new_digit = $this->change_base($new_digit, $new_base);
				}
				$new_digit = $this->pad_zeros($new_digit, $exponent_length);
				$result = $this->add_sub($result, $new_digit, $new_base, $limit_decimals);
			}
			$exponent++;
		}
		if($find_last_exponent) {
			$exponent_difference = $object->strings->strlen($result)-($object->strings->strlen($unaltered_value));
			$exponent_value = $exponent_values[0];
			$counter = 0;
			if($exponent_difference > 0) {
				while($counter < $exponent_difference) {
					$exponent_value = $this->result($exponent_value, $new_base);
					$this->maximum_base_change_exponent = $exponent_value;
					$counter++;
				}	
			} else {
				$index = $this->absolute($exponent_difference);
				$this->maximum_base_change_exponent = $exponent_values[$index];
			}
		}
		return $result;
	}
	
	public function fraction_base($value, $new_base, $base=10, $limit_decimals=false) {
		$split = $object->strings->explode('/', $value);
		$numerator = $this->change_base($split[0], $new_base, $base, $limit_decimals);
		$denominator = $this->change_base($split[1], $new_base, $base, $limit_decimals);
		return $numerator.'/'.$denominator;	
	}
	
	private function decimal_mult($value) {
		if($object->strings->strlen($value) <= 1) {
			return false;	
		}
		$digits = $object->strings->str_split($value);
		$counter = 1;	
		$is_decimal_mult = true;
		while($counter < $object->count($digits)) {
			if($digits[$counter] != 0) {
				$is_decimal_mult = false;	
			}
			$counter++;	
		}
		return $is_decimal_mult;
	}	
	
	public function all_digits_same($value) {
		$digits = $this->get_digits($value);
		$first_digit = $digits[0];
		foreach($digits as $digit) {
			if($digit != $first_digit) {
				return false;	
			}
		}
		return true;
	}
	
	public function modulus($value, $divider) {
		if($value == 0 || $divider == 0) {
			return 0;	
		}
		if($value == $divider) {
			return 0;	
		}
		$division = $this->execute_divide($value, $divider);
		$numerator = $this->fraction_values($division['remainder'])[0];
		return $numerator;
	}
	

	public function ord($value, $modulus_value) {
		$power = 1;
		$value_power = 1;
		
		while($power <= $object->strings->strlen($value)) {
			$value_power = $this->result($value_power, $value);
			$modulus = $this->modulus($value_power, $modulus_value);
			if($modulus == 1) {
				return $power;	
			}
			$power = $this->add($power, 1);	
		}
		return 1;
	}
	
	public function perfect_power($value) {
		$closest_value = NULL;
		$max_root = $this->ceil($this->natural_logarithm(['value' => $value, 'remainder' => '0/1']));
		$power = 2;
		while($this->larger($max_root, $power)) {
			$root = $this->root($value, $power);
			if($root !== (-1)) {
				return true;	
			}
			$closest_value = $this->root_closest_result;
			$power = $this->add($power, 1);
		}
		return false;
	}
		
	public function gcd($a, $b) {
		if($a == '0') {
			return $b;
		}
		return $this->gcd($this->modulus($b, $a), $a);
	}
	
	public function execute_shorten_fraction($value, $bypass_truncation=false) {
		$negative = false;
		$value_unaltered = $value.'';
		if($this->negative($value)) {
			$negative = true;	
		}
		$value = $this->absolute($value);
		$fraction_values = $this->fraction_values($value);
		
		if($fraction_values[0] === $fraction_values[1]) {
			$value = '1/1';
			if($negative) {
				return $this->negative_value($value);	
			}
			return $value;
		}
		if($fraction_values[0] == '0') {
			return '0/1';	
		}
		if($this->truncate_fractions_length > 0 && $bypass_truncation == false) {
			$denominator_length = $object->strings->strlen($fraction_values[1]);
			if($denominator_length > $this->truncate_fractions_length) {
				/*$object->log('real fraction');
				$real_fraction = $this->real_fraction($value, $this->truncate_fractions_length);
				$object->log('real fraction after');
				$whole_value = $this->whole_common($real_fraction);
				$whole_value = $this->whole_numerator($whole_value);
				if($negative) {
					$whole_value = $this->negative_value($whole_value);	
				}
				$object->log('return shorten fraction0');
				$object->log($whole_value);
				return $whole_value;*/

				/*$denominator_value_length = $denominator_length - $this->truncate_fractions_length;

				$denominator_numerator_value = $object->strings->substr($fraction_values[1], $this->truncate_fractions_length);

				$denominator_denominator_value = $object->strings->substr($fraction_values[1], 0, $this->truncate_fractions_length);

				$divide_value = ['value' => $this->pad_zeros('1', $this->truncate_fractions_length), 'remainder' => $denominator_numerator_value.'/'.$denominator_denominator_value];

				$division = $this->execute_divide($fraction_values[0], $divide_value);*/
				/*$cut_length = $denominator_length - $this->truncate_fractions_length;

				return $object->strings->substr($fraction_values[0], 0, $this->strings->strlen($fraction_values[0])-$cut_length).'/'.$object->strings->substr($fraction_values[1], 0, $this->truncate_fractions_length);*/

				$division_value = $this->pad_zeros('1', $this->truncate_fractions_length);

				$division = $this->execute_divide($fraction_values[0], $fraction_values[1]);
				$division = $this->multiply_total($division, ['value' => $division_value, 'remainder' => '0/1']);

				return $division['value'].'/'.$division_value;
			}
			return $value_unaltered;
		}
		$a = $fraction_values[0];
		$b = $fraction_values[1];
		$result = $this->shorten_fraction_gcd_sub($a, $b);	
		if($negative) {
			$result = $this->negative_value($result);	
		}
		return $result;
	}
	
	public function shorten_fraction_gcd_sub($a, $b) {
		$gcd = $this->gcd($a, $b);
		if($gcd == '1') {
			return $a.'/'.$b;	
		}
		$a_divided = $this->execute_divide($a, $gcd)['value'];
		$b_divided = $this->execute_divide($b, $gcd)['value'];	
		return $this->shorten_fraction_gcd_sub($a_divided, $b_divided);
	}
  
	public function coprime($a, $b)  { 
		if($this->gcd($a, $b) == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function modexp($a, $b, $n) {
		$c = 1;
		while($this->larger($b, 0, false)) {
			if($this->modulus($b, 2) == 1) {
				$c = $this->result($c, $a);
				$c = $this->modulus($c, $n);
			}
			$a = $this->result($a, $a);
			$a = $this->modulus($a, $n);
			
			$b = $this->bit_shift_right($b, 0);		
		}
		return $c;
	}


	
	public $base_primes = [1, 2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97];
	private $valid_prime = [1, 2, 3, 4, 5, 7, 8, 11, 14, 15, 16, 17, 18, 20, 23, 26, 28, 29, 31, 34, 41, 47, 52]; 	
	private $valid_prime_last_character = [9, 3, 1, 7];
	
	private $prime_last_digit;
		
	private $sub_prime_call_flag = false;

	private function execute_prime($value, $bypass=false) {
		$this->prime_sub_divider_candidates = [];
		$this->sub_prime_call_flag = false;
		return $this->prime($value, $bypass);	
	}
	
	private $known_primes = [];
	private $known_non_primes = [];
	private $primes_looked = [];
	private $prime_maximum_value = NULL;
	
	public function prime_p($value) {
		$prime_detection = new prime_detection($value, $this);
		return $prime_detection->pollard_check($value);	
	}

	public function prime($value, $closest_known_prime=NULL, $weak=true, $strength='8') {
		$value = $this->absolute($value);
		
		
		if($value == 0 || $value == NULL) { 			
			return false;	
		}
		if($object->strings->strlen($value) <= 2) {
			if($object->in_array($value, $this->base_primes)) {
				return true;
			}
			return false;
		}
		$digits = $object->strings->str_split($value);
		$last_value = $digits[$object->count($digits)-1];
		
		
		
		$this->prime_last_digit = $last_value;
		
		$valid = false;
		if($this->all_digits_same($value) && ($last_value != 1 || $object->strings->strlen($value) > 2)) {
			return false;	
		}
		
		if($strength != '8') {
			$this->binary_modulus->set_strength($strength);	
		}

		$object->log('start');
		
		if($this->prime_sum_verification($value) && $object->in_array($last_value, $this->valid_prime_last_character)) {
			$prime_detection = new prime_detection($value, $this);
			
			$sub_prime = $object->strings->substr($value, 1);
			$digit_sum = $this->digit_sum($value);
			$last_character = $this->get_digits($digit_sum)[0];
			$digit_sum_digit_sum = $this->digit_sum($digit_sum);
			$valid = $prime_detection->prime_division_verification($value);

			$object->log($valid);

			if(!$valid) {	
				return false;
			}													
			if($prime_detection->inspect_bases()) {
				return false;	
			}
			$object->log('after inspectbases');
			if($prime_detection->palindrome()) {
				return false;	
			}
			$object->log('after palindrome');
			if($prime_detection->repeat_value()) {
				return false;	
			}
			$object->log('after repeat_value');

			/*$object->log($object->toJSON($prime_detection->base_values));*/

			$base_six = $prime_detection->base_values[6];
			$object->log('base six value');
			$object->log($base_six);
			$base_six_digits = $this->get_digits($base_six);
			$last_digit_base_six = $base_six_digits[0];
			if($last_digit_base_six == 1 || $last_digit_base_six == 5) {	
				$value_binary = $prime_detection->base_values[2];	
				$valid = $this->binary_modulus->execute_fermat_quotient_modulus($value_binary, $value);
				$object->log('1: '.$valid);
				if(!$valid) {
					return false;
				}

				$valid = $this->prime_root_verification($value);
				$object->log('1: '.$valid);
				if(!$valid) {
					return false;
				}
				/**/

				/*if($closest_known_prime !== NULL) {
				}

				$valid = $this->binary_modulus->prime_verification_auxillary($value_binary);
				*/

				$valid = $this->prime_verification($value);	
				$object->log('2: '.$valid);
				if(!$valid) {
					return false;
				}
				$valid = !$this->prime_triangle($value);
				$object->log('3: '.$valid);
				if(!$valid) {
					return false;
				}
				$valid = $this->prime_row_offset($value);
				$object->log('4: '.$valid);
				if(!$valid) {
					return false;
				}
				$valid = $this->prime_row_offset_aux_alt($value);	
				$object->log('5: '.$valid);
				if(!$valid) {
					return false;
				}
				$valid = $this->prime_fold($value);	
				$object->log('6: '.$valid);
				if(!$valid) {
					return false;
				}
				/*$valid = $this->binary_modulus->iterative_subtraction_alt($value_binary);
				$object->log('7: '.$valid);
				if($valid != 'undetermined' && !$valid) {
					return false;
				}*/
				$valid = $this->binary_modulus->prime_validation($value_binary); 
				$object->log('8: '.$valid);
				if(!$valid) {
					return false;
				}
				/*$valid = $this->binary_modulus->iterative_subtraction($value_binary, $value);
				$object->log('9-3: '.$valid);
				if(!$valid) {
					return false;
				}*/

				if($closest_known_prime != NULL) {
					$closest_known_prime = $this->change_base($closest_known_prime, '2');
					$this->binary_modulus->set_closest_known_prime($closest_known_prime);	
					$valid = $this->binary_modulus->iterative_subtraction_alt($value_binary);
					$object->log('intermediate: '.$valid);
					if($valid == 'undetermined') {
						$valid = $this->prime_root_auxillary_fast($value);	
					}
					if(!$valid) {
						return false;
					}
				}


				/*$valid = $this->binary_modulus->prime($value_binary, $value);
				$object->log('9: '.$valid);
				if(!$valid) {
					return false;
				}*/
				

				if(!$weak) {
				
					$valid = $this->binary_modulus->prime_verification_auxillary($value_binary);
					$object->log('9-2: '.$valid);
					if(!$valid) {
						return false;
					}
				}


				if(!$valid) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
		return false;
	}

	
	public function prime_base($value) {
		$unaltered_value = $value;
		$last_base = 10;
		while(true) {
			$digits = $this->get_digits($value);
			$last_digit = $digits[0];
			if($last_digit == 0) {
				return false;
			}
			if($last_digit == $last_base || $last_digit == 1) { 
				return true;
			}
			$value = $this->change_base($unaltered_value, $last_digit);
			$last_base = $last_digit;
		}
		return false;
	}


	
	private $exclude_seven = [];
	
	
	
	private $prime_sum_value = 0;
	
	private $prime_sum_last_value;
	private $prime_sum_primary_value;		
	
	private function prime_sum_verification($value) {
		$sum = $this->digit_sum($value);
		
		$this->prime_sum_primary_value = $sum;
		$division = $this->execute_divide($sum, 3);
		if($this->fraction_values($division['remainder'])[0] == 0) {
			return false;	
		}
		
		
		
		$division = $this->execute_divide($value, $sum);		
		
		$sum_division = $this->execute_divide($sum, $value);		
		$division = $this->execute_divide($division, $sum_division);
		if($this->fraction_values($division['remainder'])[0] == 0) {
			return false;	
		}
		return true;
	}
	
	public $prime_root_result = [];
	public $prime_root_squared = [];
	public $prime_second_root = [];
	private function prime_root_verification($value) {
		$root = $this->root($value, 2);
		if($root !== (-1)) {
			return false;	
		}

		$this->prime_root_result = $object->create();
		$this->prime_root_squared = $object->create();
		$this->prime_second_root = $object->create();

		$valid = true;
		$closest_root = $this->root_closest_result;
		$squared = $this->execute_power_whole(['value' => $closest_root, 'remainder' => '0/1'], 2)['value'];
		if($this->larger($squared, $value)) {
			$closest_root = $this->subtract($closest_root, 1);	
			$squared = $this->execute_power_whole(['value' => $closest_root, 'remainder' => '0/1'], 2)['value'];
		}
		$this->prime_root_result[$value] = $closest_root;
		$this->prime_root_squared[$value] = $squared;
		
		$squared_mid_point = $closest_root;		
		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		
		$division = $this->execute_divide($remainder, $closest_root);
		
		$sub_whole = $this->result($division['value'], $closest_root);
		
		$sub_remainder = $this->subtract($remainder, $sub_whole);
		if($sub_remainder == 0) {
			return false;	
		}
		
		$prime_second_root = $this->square_root($closest_root);
		if($prime_second_root === (-1)) {
			$prime_second_root = $this->root_closest_result;	
		}
		$this->prime_second_root[$value] = $prime_second_root;
		
		$row_count = $this->add($closest_root, $division['value']);
		$whole = $this->result($row_count, $closest_root);
		while($this->larger($closest_root, $prime_second_root, false)) {
			
			if($row_count == $sub_remainder) {
				return false;	
			}
			if($row_count > $sub_remainder) {
				return true;	
			}
			
			if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
				return false;	
			}
			
			if(!$this->even($sub_remainder) && !$this->even($whole)) {
				return true;	
			}
			if(!$this->even($sub_remainder) && !$this->even($row_count)) {
				return true;	
			}
			if(!$this->even($sub_remainder) && !$this->even($closest_root)) {
				return true;	
			}
			
			$addition = $row_count;
			$sub_remainder_subtraction = $this->subtract($closest_root, $sub_remainder);
			
			if($sub_remainder_subtraction == 0) {
				return false;	
			}
			
			$sub_remainder_subtraction = $this->subtract($sub_remainder_subtraction, 1);
			$sub_remainder = $this->subtract($addition, $sub_remainder_subtraction);
			$row_count_addition = 1;
			
			$closest_root = $this->subtract($closest_root, 1);
			$row_count = $this->add($row_count, $row_count_addition);
			
			$whole = $this->result($row_count, $closest_root);
			
		}
		return true;
	}


	
	private $prime_sub_divider_candidates = [];
	
	private function prime_sub_divider_verification($value) {
		
		return $this->prime_divider_aux($value);
	}
	
	private function prime_root_auxillary_alt($value) {
		$closest_root = $this->prime_root_result[$value];
		
		
		$squared = $this->prime_root_squared[$value];
		
		$squared_mid_point = $closest_root;		
		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		$division = $this->execute_divide($remainder, $closest_root);
		$sub_whole = $this->result($division['value'], $closest_root);
		$sub_remainder = $this->subtract($remainder, $sub_whole);
		
		if($sub_remainder == 0) {
			return false;	
		}
		
		$row_count = $this->add($closest_root, $division['value']);
		$whole = $this->result($row_count, $closest_root);
		
		
		if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
			return false;	
		}
		if($row_count == $sub_remainder) {
			return false;	
		}
		
		$continue = false;
			$auxillary_division = $this->execute_divide($value, $row_count);
			if($this->fraction_values($auxillary_division['remainder'])[0] == 0) {
				return false;	
			}
			
			while($this->prime($row_count) || $this->prime($sub_remainder) || $sub_remainder == 1) {
				$sub_remainder = $this->add($row_count, $sub_remainder);	
				
				$closest_root = $this->subtract($closest_root, 1);
				
				$division = $this->execute_divide($sub_remainder, $closest_root);
				$sub_whole = $this->result($division['value'], $closest_root);
				$sub_remainder = $this->subtract($sub_remainder, $sub_whole);
				
				$row_count = $this->add($row_count, $division['value']);
				if($row_count == $value) {
					return true;	
				}
				$whole = $this->result($row_count, $closest_root);
				if($sub_remainder == 0) {
					return false;	
				}
				if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
					return false;	
				}
				if($row_count == $sub_remainder) {
					return false;	
				}
			}
			$continue = false;
			$sub_remainder_divided = $this->execute_divide($sub_remainder, $row_count)['remainder'];
			$division_shortened = $this->execute_shorten_fraction($sub_remainder_divided);
			if($sub_remainder_divided != $division_shortened) {
				return false;	
			} else {
				$continue = true;	
				$sub_remainder = $this->add($row_count, $sub_remainder);	
				
				$closest_root = $this->subtract($closest_root, 1);
				
				$division = $this->execute_divide($sub_remainder, $closest_root);
				$sub_whole = $this->result($division['value'], $closest_root);
				$sub_remainder = $this->subtract($sub_remainder, $sub_whole);
				
				$row_count = $this->add($row_count, $division['value']);
				
				$whole = $this->result($row_count, $closest_root);
				
				if($sub_remainder == 0) {
					return false;	
				}
				if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
					return false;	
				}
				if($row_count == $sub_remainder) {
					return false;	
				}
			}
		return true;
	}


	
	private function prime_triangle($value) {
		$closest_root = $this->prime_root_result[$value];
		$squared = $this->prime_root_squared[$value];
		
		$subtraction = $this->subtract($value, $squared);
		$division = $this->ceil($this->execute_divide($subtraction, $closest_root));
		
		$first_row = $this->add($closest_root, $division);
		if(!$this->even($first_row)) {
			return false;	
		}
		
		$first_row_squared = $this->execute_power_whole($first_row, 2);
		$half = $this->execute_divide($first_row_squared, 2)['value'];
		$vertex = $this->execute_divide($first_row, 2)['value'];
		$half = $this->add($half, $vertex);
		
		if($half == $value) {
			return true;	
		}
		return false;
		
		$row = $this->subtract($first_row, 1);
		$total = $this->add($first_row, $row);
		while($row > 0 && $total <= $value) {
			$row = $this->subtract($row, 1);
			$total = $this->add($total, $row);
		}
		if($total == $value) {
			return true;	
		}
		return false;
	}
	
	private $prime_sub_values = [];
	
	private function prime_row_offset($value) {
		$closest_root = $this->prime_root_result[$value];
		$squared = $this->prime_root_squared[$value];
		
		$offset = $this->subtract($value, $squared);
		
		
		$division = $this->execute_divide($offset, $closest_root);
		$mult = $this->result($division['value'], $closest_root);
		
		
		
		$rows = $this->subtract($closest_root, $this->subtract($offset, $mult));
		
		if($rows == 0) {
			return false;
		} else if($rows == 1) {
			return true;	
		}
		
		while($this->larger($rows, 1)) {
			$columns = $this->execute_divide($value, $rows);
			$remainder = $this->fraction_values($columns['remainder'])[0];
			
			
			
			$sub_column = $this->absolute($this->subtract($columns['value'], $rows));
			
			while($sub_column > 1) {
				if($sub_column != $value && $sub_column != 1) {
					$sub_division = $this->execute_divide($value, $sub_column);
					$sub_remainder = $this->fraction_values($sub_division['remainder'])[0];
					if($sub_remainder == 0) {
						return false;	
					}
					$sub_sub_column = $this->subtract($sub_column, $sub_remainder);
					
					if($sub_sub_column != 1 && $sub_sub_column != $value) {
						$sub_sub_division = $this->execute_divide($value, $sub_sub_column);
						$sub_sub_remainder = $this->fraction_values($sub_sub_division['remainder'])[0];
						if($sub_sub_remainder == 0) {
							return false;
						}
					}
					$sub_sub_column = $this->add($sub_column, $sub_remainder);
					
					if($sub_sub_column != 1 && $sub_sub_column != $value) {
						$sub_sub_division = $this->execute_divide($value, $sub_sub_column);
						$sub_sub_remainder = $this->fraction_values($sub_sub_division['remainder'])[0];
						if($sub_sub_remainder == 0) {
							return false;
						}
					}
					$sub_sub_column = $this->absolute($this->subtract($sub_division['value'], $sub_remainder));
					
					if($sub_sub_column != 1 && $sub_sub_column != $value && $sub_sub_column != 0) {
						$sub_sub_division = $this->execute_divide($value, $sub_sub_column);
						$sub_sub_remainder = $this->fraction_values($sub_sub_division['remainder'])[0];
						if($sub_sub_remainder == 0) {
							return false;
						}
					}
					
					
					$sub_column = $sub_remainder;
				}
			}			
			if($remainder == 0) {
				return false;	
			} else if($remainder == 1) { 				
				return true;	
			}
			$rows = $remainder;
		}
		
		
		
	}


	
	private function prime_row_offset_aux($value) {
		$closest_root = $this->prime_root_result[$value];
		$squared = $this->prime_root_squared[$value];
		
		$offset = $this->subtract($value, $squared);
		
		
		$division = $this->execute_divide($offset, $closest_root);
		$mult = $this->result($division['value'], $closest_root);
		
		
		
		$offset_remainder = $this->subtract($offset, $mult);
		$rows = $this->subtract($closest_root, $offset_remainder);
		$rows = $this->absolute($this->subtract($offset_remainder, $rows));
		
		if($rows == 0) {
			return false;
		} else if($rows == '1') {
			return true;	
		}
		while($this->larger($rows, '1')) {
			$columns = $this->execute_divide($value, $rows);
			$remainder = $this->fraction_values($columns['remainder'])[0];
			
			
			
			
			if($remainder == '0') {
				return false;	
			} else if($remainder == '1') { 				
				return true;	
			}
			$rows = $remainder;
		}
		
		
		
	}
	
	private function prime_row_offset_aux_alt($value) {
		$closest_root = $this->prime_root_result[$value];
		$squared = $this->prime_root_squared[$value];
		
		$offset = $this->subtract($value, $squared);
		
		
		$division = $this->execute_divide($offset, $closest_root);
		$mult = $this->result($division['value'], $closest_root);
		
		
		
		$rows = $this->subtract($closest_root, $this->subtract($offset, $mult));
		$rows = $this->add($rows, $this->result($closest_root, 2));
		
		
		
		while($this->larger($rows, 1)) {
			$columns = $this->execute_divide($value, $rows);
			$remainder = $this->fraction_values($columns['remainder'])[0];
			
			
			
			$sub_column = $this->subtract($rows, $remainder);
			while($sub_column > 1) {
				if($sub_column != $value && $sub_column != 1) {
					$sub_division = $this->execute_divide($value, $sub_column);
					$sub_remainder = $this->fraction_values($sub_division['remainder'])[0];
					if($sub_remainder == 0) {
						return false;	
					}
					$sub_sub_column = $this->subtract($sub_column, $sub_remainder);
					if($sub_sub_column != 1 && $sub_sub_column != $value) {
						$sub_sub_division = $this->execute_divide($value, $sub_sub_column);
						$sub_sub_remainder = $this->fraction_values($sub_sub_division['remainder'])[0];
						if($sub_sub_remainder == 0) {
							return false;
						}
					}
					$sub_sub_column = $this->add($sub_column, $sub_remainder);
					if($sub_sub_column != 1 && $sub_sub_column != $value) {
						$sub_sub_division = $this->execute_divide($value, $sub_sub_column);
						$sub_sub_remainder = $this->fraction_values($sub_sub_division['remainder'])[0];
						if($sub_sub_remainder == 0) {
							return false;
						}
					}
					$sub_sub_column = $this->absolute($this->subtract($sub_division['value'], $sub_remainder));
					if($sub_sub_column != 1 && $sub_sub_column != $value && $sub_sub_column != 0) {
						$sub_sub_division = $this->execute_divide($value, $sub_sub_column);
						$sub_sub_remainder = $this->fraction_values($sub_sub_division['remainder'])[0];
						if($sub_sub_remainder == 0) {
							return false;
						}
					}
					
					
					$sub_column = $sub_remainder;
				}
			}
			
			
			if($remainder == 0) {
				return false;	
			} else if($remainder == 1) { 				
				return true;	
			}
			$rows = $remainder;
		}
		
		
		
	}
	
	private function prime_fold($value) {
		$closest_root = $this->prime_root_result[$value];
		$squared = $this->prime_root_squared[$value];
		
		$squared_mid_point = $closest_root;		
		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		$division = $this->execute_divide($remainder, $closest_root);
		$sub_whole = $this->result($division['value'], $closest_root);
		$remainder = $this->subtract($remainder, $sub_whole);	
		$row_count = $this->add(1, $division['value']);
		
		$closest_root = $this->subtract($closest_root, $remainder);
		$row_count = $this->add($row_count, 1);
		
		$partition = $this->execute_divide($closest_root, $row_count);
		$partition_fill = $this->result($row_count, $partition['value']);
		$partition_remainder = $this->subtract($closest_root, $partition_fill);
		
		$partition_remainder_column = $this->add($remainder, $partition['value']);
		$result = $this->result($partition_remainder, $partition_remainder_column);
		$alternative_result = $this->result($row_count, $closest_root);
		$alternative_result = $this->add($alternative_result, $partition_fill);
		$alternative_result = $this->add($alternative_result, $partition_remainder);
		$alternative_result = $this->absolute($this->subtract($alternative_result, $result));
		$result = $this->absolute($result);
		if($result != $value && $result != 1 && $result != 0) {
			$division = $this->execute_divide($value, $result);
			if($this->fraction_values($division['remainder'])[0] == 0) {
				return false;	
			}
		}
		if($alternative_result != $value && $alternative_result != 1 && $alternative_result != 0) {
			$division = $this->execute_divide($value, $alternative_result);
			if($this->fraction_values($division['remainder'])[0] == 0) {
				return false;	
			}
		}
		return true;
	}


	private function prime_divider($value) {
		$digit_sum = $this->digit_sum($value);
		
		$division = $this->execute_divide($value, $digit_sum);
		$result_a = $this->round($division);
		$modulus = $this->modulus($value, $digit_sum);
		if($this->prime($result_a)) { 			
			return true;	
		}
				
		

		return false;
	}
	
	private function prime_divider_aux($value) {
		$digit_sum = $this->digit_sum($value);
		$final_sum = $this->final_digit_sum($digit_sum);
		
		$modulus = $this->modulus($value, $final_sum);
		if($modulus == 0 && $final_sum != 1) {
			return false;	
		}
		if($this->prime($modulus) && $this->prime($digit_sum)) { 			
			return true;	
		}
		$modexp = $this->modexp($value, $final_sum, $digit_sum);
		if($this->prime($modexp)) {
			return true;	
		}
		
	
		$first_modulus = $this->modulus($value, $digit_sum);
		if($this->prime($first_modulus)) {
			return true;	
		}
		$digit_sum_modulus_modulus = $this->modulus($digit_sum, $modulus);
		$digit_sum_first_modulus_modulus = $this->modulus($digit_sum, $first_modulus);
		if($this->prime($digit_sum_modulus_modulus) && $this->prime($digit_sum_first_modulus_modulus)) {
			return true;	
		}
		if($final_sum == 1) {
			return true;	
		}
		$second_modulus = $this->modulus($value, $first_modulus);
		if($this->prime($second_modulus)) {
			return true;	
		}
		$digit_sum_modulus = $this->digit_sum($modulus);
		$third_modulus = $this->modulus($value, $digit_sum_modulus);
		if($this->prime($third_modulus)) {
			return true;	
		}
		return false;
	}
	
	private function prime_root_auxillary_offset($value) {
		$closest_root = $this->prime_root_result[$value];
		$squared = $this->prime_root_squared[$value];
		
		$squared_mid_point = $closest_root;		
		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		$division = $this->execute_divide($remainder, $closest_root);
		$sub_whole = $this->result($division['value'], $closest_root);
		$remainder = $this->subtract($remainder, $sub_whole);
		
		if($this->prime($remainder) && $remainder != 1) {
			$sub_division = $this->execute_divide($closest_root, $remainder);
			if($this->fraction_values($sub_division['remainder'])[0] == 0) {
				return true;	
			}
		}
		$fraction = $remainder.'/'.$closest_root;
		
		
		
		$fraction_values = $this->fraction_values($fraction);
		$remainder = $fraction_values[0];
		$closest_root = $fraction_values[1];
		
		
		
		$row_count = $this->add($closest_root, $division['value']);
		$whole = $this->result($row_count, $closest_root);	
		$sub_remainder = $remainder;
		
		$prime_third_root = $this->square_root($this->prime_second_root[$value]);
		if($prime_third_root === (-1)) {
			$prime_third_root = $this->root_closest_result;	
		}
		$prime_third_root = $this->add($prime_third_root, 1);
		$max_keys = $this->result(2, $prime_third_root);
		
		while($this->larger($closest_root, $prime_third_root, false)) { 			
			if($row_count == $sub_remainder) {
				return false;	
			}
			
			if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
				return false;	
			} else {
				
			}
			
			
			if(!$this->even($sub_remainder) && !$this->even($closest_root) && !$this->even($row_count)  && !$this->even($whole)) { 				
				if($sub_remainder != 0 && $row_count != $sub_remainder) {
					return true;	
				}
			}
			
			
			
			$addition = $row_count;			
			if($sub_remainder == 0) {
				return false;	
			}
			$sub_remainder_subtraction = $this->subtract($closest_root, $sub_remainder);
			
			
			
			$sub_remainder_subtraction = $this->subtract($sub_remainder_subtraction, 1);
			$sub_remainder = $this->subtract($addition, $sub_remainder_subtraction);
			
			$closest_root = $this->subtract($closest_root, 1);
			$row_count_addition = $this->execute_divide($sub_remainder, $closest_root)['value'];
			
			$sub_remainder_subtraction_value = $this->result($closest_root, $row_count_addition);
			$sub_remainder = $this->subtract($sub_remainder, $sub_remainder_subtraction_value);
			
			$row_count_addition = $this->add($row_count_addition, 1);
			
			$row_count = $this->add($row_count, $row_count_addition);
			
			$whole = $this->result($row_count, $closest_root);
		}
		return true;
		
	}
	
	private function prime_root_auxillary_secondary($value) {
		
		$value_digit_sum = $this->digit_sum($value);
		$value_modulus = $this->modulus($value, $value_digit_sum);
		
		

		$valid = true;
		return $this->prime_root_auxillary_secondary_sub($value);
	}
	
	
	public function is_zero($value) {
		if($value == 0) {
			return true;	
		}
		return false;
	}
	
	private function prime_root_alternating($value) {
		$prime_detection = new prime_detection($value, $this);
		return $prime_detection->root_alternating($this->prime_second_root[$value]);	
	}
		
	private function prime_root_auxillary_fast($value) {
		$closest_root = $this->prime_root_result[$value];
		
		$squared = $this->prime_root_squared[$value];
		
		$squared_mid_point = $closest_root;
		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		$division = $this->execute_divide($remainder, $closest_root);
		$sub_whole = $this->result($division['value'], $closest_root);
		$sub_remainder = $this->subtract($remainder, $sub_whole);
		
		if($sub_remainder == 0) {
			return false;	
		}
		
		
		
		
		$row_count = $this->add($closest_root, $division['value']);
		$whole = $this->result($row_count, $closest_root);
		
		$sub_remainders = [];
		
		$prime_third_root = $this->root($closest_root, 3);
		if($prime_third_root === (-1)) {
			$prime_third_root = $this->root_closest_result;	
		}
		$prime_third_root = $this->add($prime_third_root, 1);
		$max_keys = $this->result(2, $prime_third_root);
		$sub_remainder_subtraction = 0;
		
		$sub_subtraction_value = NULL;
		$last_row_count_pseudo_value = NULL;
		$whole = $this->result($row_count, $closest_root);
				
		
		while($this->larger($closest_root, $prime_third_root, false)) { 
			if($row_count == $sub_remainder) {
				return false;	
			}
			
			
			
			if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
				return false;	
			} else {
			}
			
			
			$addition = $row_count;			
			if($sub_remainder == 0) {
				return false;	
			}
			$sub_remainder_subtraction = $this->subtract($closest_root, $sub_remainder);
			$sub_remainder_value = $sub_remainder_subtraction;
			
		
			$sub_remainder_subtraction = $this->subtract($sub_remainder_subtraction, 1);
			$sub_remainder = $this->subtract($addition, $sub_remainder_subtraction);
			
			$sub_remainder_unaltered = $sub_remainder;
			
			
			$closest_root = $this->subtract($closest_root, 1);
			
			
			$row_count_addition = $this->division->fast_floor_divide($sub_remainder, $closest_root);
			
			
			$sub_remainder_subtraction_value = $this->result($closest_root, $row_count_addition);
			
			
			$sub_remainder = $this->subtract($sub_remainder, $sub_remainder_subtraction_value);
			
			$row_count_addition = $this->add($row_count_addition, 1);
			
			$row_count = $this->add($row_count, $row_count_addition);
			
			$whole = $this->subtract($value, $sub_remainder);
			
			$sub_subtraction_value = $sub_remainder_subtraction_value;
		}
		return true;
	}
						
	private function prime_root_auxillary($value) {
		$closest_root = $this->prime_root_result[$value];
		
		
		$squared = $this->prime_root_squared[$value];
		
		$squared_mid_point = $closest_root;		
		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		$division = $this->execute_divide($remainder, $closest_root, false, false);
		$sub_whole = $this->result($division['value'], $closest_root);
		$sub_remainder = $this->subtract($remainder, $sub_whole);
		
		if($sub_remainder == 0) {
			return false;	
		}
		
		$row_count = $this->add($closest_root, $division['value']);
		$whole = $this->result($row_count, $closest_root);
		
		$sub_remainders = [];
		
		$prime_third_root = $this->root($this->prime_second_root[$value], 2);
		if($prime_third_root === (-1)) {
			$prime_third_root = $this->root_closest_result;	
		}
		$prime_third_root = $this->add($prime_third_root, 1);
		$max_keys = $this->result(2, $prime_third_root);		
		$sub_remainder_subtraction = 0;
		
		
		while($this->larger($closest_root, $prime_third_root, false)) { 			
			if($row_count == $sub_remainder) {
				return false;	
			}
			
			if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
				return false;	
			} else {
				
			}
			
			
			if(!$this->even($sub_remainder) && !$this->even($closest_root) && !$this->even($row_count)  && !$this->even($whole)) { 				
				if($sub_remainder != 0 && $row_count != $sub_remainder) {
					return true;	
				}
			}
			
			
			
			
			$addition = $row_count;			
			if($sub_remainder == 0) {
				return false;	
			}
			$sub_remainder_subtraction = $this->subtract($closest_root, $sub_remainder);
			
			
			
			$sub_remainder_subtraction = $this->subtract($sub_remainder_subtraction, 1);
			$sub_remainder = $this->subtract($addition, $sub_remainder_subtraction);
			
			
			
			
			
			$sub_remainder_unaltered = $sub_remainder;
			
			
			$closest_root = $this->subtract($closest_root, 1);
			
			
			$row_count_addition = $this->division->fast_floor_divide($sub_remainder, $closest_root);			
			
			
			
			$sub_remainder_subtraction_value = $this->result($closest_root, $row_count_addition);
			
			$sub_remainder = $this->subtract($sub_remainder, $sub_remainder_subtraction_value);
			
			$row_count_addition = $this->add($row_count_addition, 1);
			
			$row_count = $this->add($row_count, $row_count_addition);
			
			$whole = $this->result($row_count, $closest_root);
		}
		return true;
	}
	
	private function prime_root_auxillary_auxillary($value) {
		$closest_root = $this->prime_root_result[$value];
		
		
		$squared = $this->prime_root_squared[$value];
		
		$squared_mid_point = $closest_root;		if($this->larger($squared_mid_point, $value)) {
			$squared_mid_point = $this->subtract($value, 1);	
		}
		$remainder = $this->subtract($value, $squared);
		$division = $this->execute_divide($remainder, $closest_root);
		$sub_whole = $this->result($division['value'], $closest_root);
		$sub_remainder = $this->subtract($remainder, $sub_whole);
		
		if($sub_remainder == 0) {
			return false;	
		}
		
		$row_count = $this->add($closest_root, $division['value']);
		$whole = $this->result($row_count, $closest_root);
		
		$sub_remainders = [];
		
		$prime_third_root = $this->square_root($this->prime_second_root[$value]);
		if($prime_third_root === (-1)) {
			$prime_third_root = $this->root_closest_result;	
		}
		$prime_third_root = $this->add($prime_third_root, 1);
		$max_keys = $this->result(2, $prime_third_root);		
		while($this->larger($closest_root, $prime_third_root, false)) { 			
			if($row_count == $sub_remainder) {
				return false;	
			}
			
			if(($this->even($sub_remainder) && $this->even($row_count)) || ($this->even($sub_remainder) && $this->even($closest_root)) || ($this->even($sub_remainder) && $this->even($whole))) {
				return false;	
			} else {
				
			}
			
			
			if(!$this->even($sub_remainder) && !$this->even($closest_root) && !$this->even($row_count)  && !$this->even($whole)) { 				
				if($sub_remainder != 0 && $row_count != $sub_remainder) {
					return true;	
				}
			}
			
			
			
			$addition = $row_count;			
			if($sub_remainder == 0) {
				return false;	
			}
			$sub_remainder_subtraction = $this->subtract($closest_root, $sub_remainder);
			
			
			
			$sub_remainder_subtraction = $this->subtract($sub_remainder_subtraction, 1);
			$sub_remainder = $this->subtract($addition, $sub_remainder_subtraction);
			
			
			
			
			
			
			$sub_remainder_unaltered = $sub_remainder;
			
			
			$closest_root = $this->subtract($closest_root, 1);
			$row_count_addition = $this->execute_divide($sub_remainder, $closest_root)['value'];
			
			
			$sub_remainder_subtraction_value = $this->result($closest_root, $row_count_addition);
			
			$sub_remainder = $this->subtract($sub_remainder, $sub_remainder_subtraction_value);
			
			$row_count_addition = $this->add($row_count_addition, 1);
			
			$row_count = $this->add($row_count, $row_count_addition);
			
			$whole = $this->result($row_count, $closest_root);
			
			
		}
		return true;
	}
		
	private function prime_verification($value) {
		$split = $this->execute_divide($value, 2)['value'];
		
		
		$select_amount = 1;
		$select_remainder = $split;
		$select_count = 0;
		
		
		$row_count = 2;
		
		
		
		$select_count = $this->execute_divide($this->subtract($split, 2), 3)['value'];
		$select_amount = $this->result($select_count, 2);
		$select_amount = $this->add($select_amount, 1);
		
		
		$valid_select_amount = $select_amount;
		$valid_select_count = $select_count;
		
		
		$last_split = $split;
		$last_select_amount = $valid_select_amount;
		$select_remainder = $this->subtract($split, $valid_select_count);
		if($valid_select_amount == $select_remainder && $valid_select_amount > 1) {
			
			return false;	
		}
		while($last_split > $row_count) {
			$last_split = $split;
			$last_row_count = $row_count;
			$split = $select_remainder;
			
			$whole = $this->result($split, $row_count);
			
			
			
			$split_interval = $this->subtract($split, $last_select_amount);
			
			
			
			if($this->even($whole) && $this->even($last_select_amount)) {
				return false;
			} else if($this->even($whole) && !$this->even($last_select_amount)) {
				
				return true;
			} 
			
			
			$select_count = $this->subtract($split, $last_select_amount);
			
			
			$select_amount = $this->result($select_count, $row_count);
			
			$select_remainder = $this->subtract($split, $select_count);
			
			
			
			
			if($last_split == $row_count || $select_amount < 0) {
				return false;
			}
			if($select_amount == 0) {
				return false;	
			}
			if($select_amount == $select_remainder) {
				return false;	
			}
			
			$valid_select_count = $select_count;
			$valid_select_amount = $select_amount;
			
			$last_remainder = $this->subtract($split, $last_select_amount);
			$last_select_amount = $this->subtract($select_amount, $last_remainder);
			if($last_select_amount == 0) {
				return false;	
			}
			if($last_select_amount > 0) {			
				$row_count = $this->add($row_count, 1);	
			}
			
			if($row_count > $value) {
				return false;	
			}
			
		}
		return true;
	}
	
	private function prime_verification_aux($value) {
		$split = $this->execute_divide($value, 2)['value'];
		
		
		$select_amount = 1;
		$select_remainder = $split;
		$select_count = 0;
		
		$valid_select_amount = $select_amount;
		$valid_select_count = $select_count;
		
		$row_count = 2;
		
		while($this->larger($select_remainder, $select_amount, true)) {
			$valid_select_count = $select_count;
			
			$valid_select_amount = $select_amount;	
			$select_count = $this->add($select_count, 1);
			$select_amount = $this->result($select_count, 2);
			$select_amount = $this->add($select_amount, 1);
			$select_remainder = $this->subtract($split, $select_count);
			
		}
		
		
		$last_split = $split;
		$last_select_amount = $valid_select_amount;
		$select_remainder = $this->subtract($split, $valid_select_count);
		if($valid_select_amount == $select_remainder && $valid_select_amount > 1) {
			
			return false;	
		}
		while($last_split > $row_count) {
			$last_split = $split;
			$last_row_count = $row_count;
			$split = $select_remainder;
			
			$whole = $this->result($split, $row_count);
			
			
			$split_interval = $this->subtract($split, $last_select_amount);
			
			
			
			if($this->even($whole) && $this->even($last_select_amount)) {
				return false;
			} else if($this->even($whole) && !$this->even($last_select_amount)) {
				if($split_interval == 1) {
					return true;	
				}
				$sub_verification = $this->prime_verification($last_select_amount);
				if($sub_verification) {
					return true;	
				}
			} 
			
			
			$select_count = $this->subtract($split, $last_select_amount);
			
			
			$select_amount = $this->result($select_count, $row_count);
			
			$select_remainder = $this->subtract($split, $select_count);
			
			
			
			
			if($last_split == $row_count || $select_amount < 0) {
				return false;
			}
			if($select_amount == 0) {
				return false;	
			}
			if($select_amount == $select_remainder) {
				return false;	
			}
			
			$valid_select_count = $select_count;
			$valid_select_amount = $select_amount;
			
			$last_remainder = $this->subtract($split, $last_select_amount);
			$last_select_amount = $this->subtract($select_amount, $last_remainder);
			if($last_select_amount == 0) {
				return false;	
			}
			if($last_select_amount > 0) {			
				$row_count = $this->add($row_count, 1);	
			}
			
			if($row_count > $value) {
			}
		}
		return true;
	}
	

	public function mod_add($a, $b, $n) {
		$sum = $this->add($a, $b);
		return $this->modulus($sum, $n);	
	}
	
	public function mod_mult($a, $b, $n) {
		if($this->larger($a, $n)) {
			$a = $this->modulus($a, $n);	
		}
		if($this->larger($b, $n)) {
			$b = $this->modulus($b, $n);
		}
		$multiplication = $this->result($a, $b);
		return $this->modulus($multiplication, $n);
		
		
	}
	
	private function sprp($n, $a) {
		if($n == $a) {
			return true;	
		}
		$d = $this->subtract($n, 1);
		$s = 1;
		while($this->binary_and($d = $this->bit_shift_right($d, 1), 1) == 0) {
			$s = $this->add($s, 1);	
		}
		$b = $this->modexp($a, $d, $n);
		if($b == 1) {
			return true;	
		}
		$b_addition = $this->add($b, 1);
		if($b_addition == $n) {
			return true;	
		}
		while($this->larger($s, 1, false)) {
			$b = $this->mod_mult($b, $b, $n);
			if($this->add($b, 1)) {
				return true;	
			}
			$s = $this->subtract($s, 1);
		}
		return false;
	}
	
	public function absolute_division($value, $divider) {
		if($this->larger($value, $divider))	{
			return $this->execute_divide($value, $divider);	
		}
		return $this->execute_divide($divider, $value);
	}
	
	private function primality_check_alt($value) {
		$digit_sum = $this->digit_sum($value);
		$modulus = $this->modulus($value, $digit_sum);
		if(!$this->prime($modulus) && $modulus != 0) {
			$division = $this->execute_divide($digit_sum, $modulus);
			if($this->fraction_values($division['remainder'])[0] == 0) {
				return false;	
			}
		}
		
		$d = $value;
		while($this->larger($d, $digit_sum, false)) {
			$modulus = $this->mod_mult($d, $digit_sum, $value);
			$d = $this->bit_shift_right($d, 1);
			if(!$this->prime($modulus) && $modulus != 0) {
				$division = $this->execute_divide($digit_sum, $modulus);
				if($this->fraction_values($division['remainder'])[0] == 0) {
					return false;	
				}
			}
			if($modulus == 0) {
			}
		}
		return true;
	}
		
	public function execute_binary_modulus($value, $divider) {
		return $this->binary_modulus->execute_modulus($value, $divider);
		/*$binary_division = new binary_division($this);
		return $binary_division->divide($value, $divider);*/
	}
	
	public function execute_binary_modulus_alt($value, $divider) {
		$binary_division = new binary_division($this);
		return $binary_division->divide($value, $divider);
	}

	public function binary_division($value, $divider) {
		$binary_division = new binary_division($this);
		$remainder = $binary_division->divide($value, $divider);
		return [
			'quotient' => $binary_division->get_quotient(),
			'numerator' => $remainder
		];
	}

	public function binary_and($a, $b, $to_decimal=true, $change_base=true) {
		if($change_base) {
			$a = $this->change_base($a, 2);
			$b = $this->change_base($b, 2);
		}
		$strlen_a = $object->strings->strlen($a);
		$strlen_b = $object->strings->strlen($b);
		$difference = $this->absolute($this->subtract($strlen_a, $strlen_b));
		if($strlen_a > $strlen_b) {	
			$b = $this->pad_zeros($b, $difference, true);	
		} else if($strlen_b > $strlen_a) {
			$a = $this->pad_zeros($a, $difference, true);
		}
		$digits_a = $this->get_digits($a);
		$digits_b = $this->get_digits($b);
		$result = '';
		foreach($digits_a as $key => $digit_a) {
			if($object->isset($digits_b[$key])) {
				$digit_b = $digits_b[$key];
				if($digit_a == 1 && $digit_b == 1) {
					$result .= '1';	
				} else {
					$result .= '0';	
				}
			}
		}
		$result = $object->strings->strrev($result);
		if($to_decimal) {
			$result = $this->change_base_decimal($result, 2);
		}
		return $result;
	}
	
	public function binary_or($a, $b, $to_decimal=true, $change_base=true) {
		if($change_base) {
			$a = $this->change_base($a, 2);
			$b = $this->change_base($b, 2);
		}
		$strlen_a = $object->strings->strlen($a);
		$strlen_b = $object->strings->strlen($b);
		$difference = $this->absolute($this->subtract($strlen_a, $strlen_b));
		if($strlen_a > $strlen_b) {	
			$b = $this->pad_zeros($b, $difference, true);	
		} else if($strlen_b > $strlen_a) {
			$a = $this->pad_zeros($a, $difference, true);
		}
		$digits_a = $this->get_digits($a);
		$digits_b = $this->get_digits($b);
		$result = '';
		foreach($digits_a as $key => $digit_a) {
			if($object->isset($digits_b[$key])) {
				$digit_b = $digits_b[$key];
				if($digit_a == 1 || $digit_b == 1) {
					$result .= '1';	
				} else {
					$result .= '0';	
				}
			} else {
				$result .= $digit_a;	
			}
		}
		$result = $object->strings->strrev($result);
		if($to_decimal) {
			$result = $this->change_base_decimal($result, 2);
		}
		return $result;
	}
		
	public function binary_xor($a, $b, $to_decimal=true, $change_base=true) {
		if($change_base) {
			$a = $this->change_base($a, 2);
			$b = $this->change_base($b, 2);
		}
		$strlen_a = $object->strings->strlen($a);
		$strlen_b = $object->strings->strlen($b);
		$difference = $this->absolute($this->subtract($strlen_a, $strlen_b));
		if($strlen_a > $strlen_b) {	
			$b = $this->pad_zeros($b, $difference, true);	
		} else if($strlen_b > $strlen_a) {
			$a = $this->pad_zeros($a, $difference, true);
		}
		$digits_a = $this->get_digits($a);
		$digits_b = $this->get_digits($b);
		$result = '';
		foreach($digits_a as $key => $digit_a) {
			if($object->isset($digits_b[$key])) {
				$digit_b = $digits_b[$key];
				if($digit_a == 1 && $digit_b == 1) {
					$result .= '0';
				} else if($digit_a == 1 || $digit_b == 1) {
					$result .= '1';	
				} else {
					$result .= '0';	
				}
			} else {
				$result .= $digit_a;	
			}
		}
		$result = $object->strings->strrev($result);
		if($to_decimal) {
			$result = $this->change_base_decimal($result, 2);
		}
		return $result;
	}
	
	public function binary_and_inverse($value, $b) {
		$value = $this->binary_negation($value);
		$result = $this->binary_and($value, $b, false, false);	
		$result = $this->binary_negation($result);
		return $result;
	}
	
	public function binary_negation($a) {
		$digits = $object->strings->str_split($a);
		
		$result = '';
		foreach($digits as $digit) {
			if($digit == '0') {
				$result .= '1';	
			} else {
				$result .= '0';	
			}
		}
		return $result;
	}
			
	public function prime_sum($value, $primary=true) {
		$prime_values = [];
		$digits = $object->strings->str_split($value);
		$sum = 0;	
		foreach($digits as $digit) {
			$sum = $this->add($sum, $digit);		
		}
		
		if($primary) {
			$this->prime_sum_primary_value = $sum;
			$division = $this->execute_divide($sum, 3);
			if($this->fraction_values($division['remainder'])[0] == 0) {
				return [false];	
			}
			
			
			
			$division = $this->execute_divide($value, $sum);			
			
			$sum_division = $this->execute_divide($sum, $value);			
			$division = $this->execute_divide($division, $sum_division);
			if($this->fraction_values($division['remainder'])[0] == 0) {
				return [false];	
			}
		}
		
		
		if($object->strings->strlen($sum) == 1) {
			$this->prime_sum_value = $sum;
			if($object->in_array($sum, $this->base_primes)) {
				return [true];	
			}
			return [false];
		}
		
		$this->prime_sum_last_value = $sum;
		
		$prime = $this->prime($sum);
		$prime_values[] = $prime;
			
		$prime_values = $object->concat($prime_values, $this->prime_sum($sum, false));
		return $prime_values;
	}
	
	private function supplement_primes($sum) {
		$highest_value = $this->valid_prime[$object->count($this->valid_prime)-1];
		if($this->larger($sum, $highest_value)) {
			$counter = $this->add($highest_value, 1);
			while($this->larger($sum, $counter)) {
				if($this->prime($counter)) {
					$this->log_prime($counter);	
				}
				$counter = $this->add($counter, 1);	
			}
		}
	}


	public function pi() {
		return $this->execute_divide('355', '113');	
	}
	
	public function parse_value($value) {
		if($object->strings->strpos($value, '|') != (-1)) {
			$split = $object->strings->explode('|', $value);
			$value = [
				'value' => $split[0],	
				'remainder' => $split[1]
			];
			return $value;
		}
		return [
			'value' => $value,
			'remainder' => '0/1'
		];
	}
	
	public function result_value($value) {
		if($object->item_is_array($value)) {
			return $value['value'].'|'.$value['remainder'];	
		}
		return $value;
	}

	public function result_multiple($values) {
		if($values->length == 0) {
			return '0';
		}
		$value = NULL;
		if($object->item_is_array($values[0])) {
			$value = ['value' => '1', 'remainder' => '0/1'];
			foreach($values as $item_value) {
				$value = $this->multiply_total($value, $item_value);
			}
		} else {
			$value = '1';
			foreach($values as $item_value) {
				$value = $this->result($value, $item_value);
			}
		}
		return $value;
	}
	
	public function get_trigonometry($slope, $cot_precise=false, $crd_precise=false) {
		$trigonometry = $this->trigonometry;
		$trigonometry->cot_precise = $cot_precise;
		$trigonometry->crd_precise = $crd_precise;
		return $trigonometry->point($slope);	
	}
	
	public function trigonometry_radian($radian, $cot_precise=false, $crd_precise=false) {
		/*$trigonometry = new trigonometry($this);*/
		$trigonometry = $this->trigonometry;
		$trigonometry->cot_precise = $cot_precise;
		$trigonometry->crd_precise = $crd_precise;
		return $trigonometry->radian($radian);	
	}
	
	public function sine($radian, $precision=NULL) {
		return $this->trigonometry->sine($radian, $precision);	
	}
	
	public function cosine($radian, $precision=NULL) {
		return $this->trigonometry->cosine($radian, $precision);	
	}
	
	public function arctan($radian, $precision=NULL) {
		return $this->trigonometry->arctan($radian, $precision);	
	}
	
	public function arccot($radian, $precision=NULL) {
		return $this->trigonometry->arccot($radian, $precision);	
	}
	
	public function arccos($radian, $precision=NULL) {
		return $this->trigonometry->arccos($radian, $precision);	
	}
	
	public function arcsin($radian, $precision=NULL) {
		return $this->trigonometry->arcsin($radian, $precision);	
	}
}


?>