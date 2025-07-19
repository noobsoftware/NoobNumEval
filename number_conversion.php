<?

class number_conversion {
	
	private $evaluation;

	public function __construct($evaluation) {
		$this->evaluation = $evaluation;
		$this->result = '';
	}
	
	private $result = '';

	public function convert($value, $base) {
		$result = '';
		if(!$object->item_is_array($value)) {
			$value = ['value' => $value, 'remainder' => '0/1'];
		}
		while($this->evaluation->larger($value['value'], '1', false)) {
			$value = $this->evaluation->execute_divide($value, $base);
			$remainder = $this->evaluation->fraction_values($value['remainder'])[0];
			$result .= $remainder;	
			$value = ['value' => $value['value'], 'remainder' => '0/1'];
		}
		if($value['value'] == '1') {
			$result .= '1';	
		}
		return $object->strings->strrev($result);
	}
}

?>