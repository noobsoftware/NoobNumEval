<?

class prime_factors_alt2 {

	private $value;

	private $evaluation;

	public function __construct($value, $evaluation) {
		$this->value = $value;

		$this->evaluation = $evaluation;
	}

	public function get() {
		$value = $this->value;
		if($this->evaluation->prime($value)) {
			return [$value];
		}

		$factors = [];

		$factors_alt = [];

		$old_base = 10;
		$counter = 0;
		$last_first_digit = 0;
		$first_digit_appendix = '';
		$main_value = NULL;

		$last_value = NULL;

		$is_prime = false;

		while($value != 0 && $object->strings->strlen($value) > 1 && !$this->evaluation->prime($value, NULL, true)) {
			$digits = $this->evaluation->get_digits($value);
			$first_digit = $digits[0];

			if($first_digit == 1) {
				$first_digit = $last_first_digit;
			}
			if($main_value !== NULL) {
				$first_digit = $main_value;
				$main_value = NULL;
			}
			$first_digit_appendix = '';

			if($first_digit == 0 || $first_digit == 1) {
				$sub_call = $this->factor_sub($value);

				$get_value = $this->evaluation->prime_factors_alt($sub_call['factor']);
				$factors_alt = $object->concat($factors_alt, $get_value);

				$value = $sub_call['secondary'];
			} else {
				$first_digit_base = $this->evaluation->modulus($value, $first_digit);

				if($first_digit_base == 1) {
					$first_digit_base = $first_digit.$first_digit_base;

				}

				if($first_digit_base == 0) {
					$first_digit_base = $first_digit;
				}

				$old_base = $first_digit;

				$original_value = $value;
				$value = $this->evaluation->execute_divide($value, $first_digit_base);
				$fraction_values = $this->evaluation->fraction_values($value['remainder']);
				$numerator = $fraction_values[0];
				$denominator = $fraction_values[1];

				if($numerator == 0) {
					$value = $value['value'];


					$factors_addition = $this->evaluation->prime_factors_alt($first_digit_base);


					$factors_alt = $object->concat($factors_alt, $factors_addition);
				} else {
					$sub_call = $this->factor_sub($original_value, $numerator);
					$factors_addition = $this->evaluation->prime_factors_alt($sub_call['factor']);

					$factors_alt = $object->concat($factors_alt, $factors_addition);
					$value = $sub_call['secondary'];
				}
				$last_value = $value;
				$last_first_digit = $first_digit;
				$counter++;
			}
		}
		$factors_alt[] = $value;

		return $factors_alt;
	}

	public function pollard_sub($value, $n, $start_value=1) {
		$x = $this->evaluation->result($value, $value);
		$x = $this->evaluation->add($x, $start_value);
		$x = $this->evaluation->modulus($x, $n);
		
		return $x;
	}

	public function factor_sub($n, $start_value=1) {
		$x = '2';
		$y = '2';

		$d = '1';
		while($d == '1') {
			$x = $this->pollard_sub($x, $n, $start_value);
			$y = $this->pollard_sub($this->pollard_sub($y, $n, $start_value), $n, $start_value);
			$d = $this->evaluation->gcd($this->evaluation->absolute($this->evaluation->subtract($x, $y)), $n);	
		}
		return ['factor' => $d, 'secondary' => $division = $this->evaluation->execute_divide($n, $d)['value']];
	}

}

?>