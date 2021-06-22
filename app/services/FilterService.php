<?php
namespace Cartrack\Services;

class FilterService
{
	private $get;
	private $getData = array();

	public function __construct(array $get)
	{
		$this->get = $get;
	}

	public function filterData(): array
	{
		if (count($this->get)) {
			if (isset($this->get['first_name'])) {
				$this->getData[] = array(
					'name'   => 'first_name',
					'value'  => $this->get['first_name'],
					'type'   => 'string',
					'clause' => 'where'
				);
			}
			
			if (isset($this->get['last_name'])) {
				$this->getData[] = array(
					'name'   => 'last_name',
					'value'  => $this->get['last_name'],
					'type'   => 'string',
					'clause' => 'where'
				);
			}
			
			if (isset($this->get['email'])) {
				$this->getData[] = array(
					'name'   => 'email',
					'value'  => $this->get['email'],
					'type'   => 'string',
					'clause' => 'where'
				);
			}
			
			if (isset($this->get['birth_date'])) {
				$this->getData[] = array(
					'name'   => 'birth_date',
					'value'  => $this->get['birth_date'],
					'type'   => 'date',
					'clause' => 'where'
				);
			} else {
				if (isset($this->get['birth_date_from']) && isset($this->get['birth_date_to'])) {
					$this->getData[] = array(
						'name'   => 'birth_date',
						'value'  => $this->get['birth_date_from'].'/'.$this->get['birth_date_to'],
						'type'   => 'date',
						'clause' => 'where_between'
					);
				}
			}
		}
		
		return $this->getData;
	}
}