<?

class binary_conversion {
	
	private $decimal_value;

	private $binary_powers;

	private $evaluation;

	public function __construct($decimal_value, $evaluation) {
		$this->evaluation = $evaluation;
		$this->decimal_value = $decimal_value;
	
		$this->binary_powers = [];
		$index = 0;
		$mult_value = 1;
		while($this->evaluation->larger($decimal_value, $mult_value)) {
			$this->binary_powers[] = $mult_value;
			$mult_value = $this->evaluation->result($mult_value, 2);
			$index = $this->evaluation->add($index, 1);
		}
		$this->result_index = [];
	}

	private $result_index;

	public function get() {
		$decimal_value = $this->decimal_value;
		$index = $this->binary_powers->length-1;
		foreach($this->binary_powers as $binary_decimal_value) {
			if($this->evaluation->larger($decimal_value, $binary_decimal_value)) {
				$this->result_index[] = $index;
				$decimal_value = $this->evaluation->subtract($decimal_value, $binary_decimal_value);
			}
		}
		$result = '';
		$result_index = $object->reverse($this->result_index);
		$last_index_value = 0;
		foreach($result_index as $index_value) {
			$padding = $index_value - $last_index_value - 1;
			$result = $this->evaluation->pad_zeros('1', $padding).$result;
		}
		return $result;
	}
}

?>