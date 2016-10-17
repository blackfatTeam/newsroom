<?php
//=======================================================================
// Description:	Date & Time utility class
// Author:	Sirichai Meemutha (jjoi@gayji.net)
// Ver:		$Id: class.DateUtil.php v1.02
//
// License:	This code is released under GPL
// Copyright (C) 2005 JF Systems
//========================================================================

namespace app\lib;

class DateUtil {
	public $ts;

	public function __construct($ts = NULL) {
		if (is_null($ts))
			$ts = time();
			
		$this -> ts = $ts;
	}
	
	// usage ex: list($Y,$m,$j) = DateUtil::dateParts("Ymj",$time_var);
	public static function dateParts($dateFormat,$t = NULL)
	{
		$arr_keys = preg_split('//', $dateFormat, -1, PREG_SPLIT_NO_EMPTY);
		while(list($key,$val) = each($arr_keys))
		{
			$value = is_null($t)?NULL:date($val,$t);
			$arr_output[$val] = $value;
			$arr_output[$key] = $value;
		}
		return $arr_output;
	}
	
	public function duration($ts) {
		$distance = $this->ts - $ts;
		list($Y, $n, $j) = self::dateParts('Ynj', $ts);
		$days = intval(($this->ts - $ts) / self::SECS_PER_DAY);
	
		// new timestamp, substract yer
		$years = intval($days / 365);		
		$ts = mktime(0, 0, 0, $n, $j, $Y + $years);
		list($Y, $n, $j) = self::dateParts('Ynj', $ts);		
		$days = intval(($this->ts - $ts) / self::SECS_PER_DAY);

		$months = intval($days / 30);
		$ts = mktime(0, 0, 0, $n + $months, $j, $Y);
		list($Y, $n, $j) = self::dateParts('Ynj', $ts);
		$days = intval(($this->ts - $ts) / self::SECS_PER_DAY);
	
		return array(
			'year'=>$years,
			'month'=>$months,
			'day'=>$days,
		);
	}
	
	/**
	 * ให้ค่าช่วงเวลาเป็น string ภาษาไทย
	 * @param int $seconds จำนวนวินาที
	 * @return string
	 */
	public static function durationText($seconds, $mode = 'short') {
		$retVal = '';
		$units = intval($seconds / 3600);
		if ($units) {
			$retVal .= "$units ชั่วโมง";
			$seconds %= 3600;
		}
	
		$units = intval($seconds / 60);
		if ($units) {
			$retVal .= " $units นาที";
			$seconds %= 60;
			$cnt++;
		}
		if ($mode == 'short') return $retVal;		
	
		if ($seconds)
			$retVal .= " $seconds วินาที";
	
		return $retVal;
	}
	
	/**
	 * ให้ค่า timestamp ของเที่ยงคืนของวันแรกของเดือนที่ระบุโดย timestamp
	 * @param int $ts timestamp ของเดือนที่ต้องการหา
	 * @return int
	 */
	public function getFdom($ts = NULL) {
		if (!is_numeric($ts))
			$ts = $this -> ts;
			
		list($cal_n, $cal_Y) = self::dateParts('nY', $ts);	
		return mktime(0, 0, 0, $cal_n, 1, $cal_Y);
	}
	
	/**
	 * ให้ค่า timestamp ของเที่ยงคืนวันสุดท้ายของเดือนที่ระบุโดย timestamp
	 * @param int $ts timestamp ของเดือนที่ต้องการหา
	 * @return int
	 */
	public  function getLdom($ts = NULL) {
		if (!is_numeric($ts))
			$ts = $this -> ts;
			
		list($cal_n, $cal_Y) = self::dateParts('nY', $ts);	
		return mktime(0, 0, 0, $cal_n + 1, 0, $cal_Y);
	}
	
	/**
	 * หาค่า date(j) - วันที่ที่มากที่สุด ในเดือนที่ระบุโดย timestamp
	 * @param int $ts
	 * @return int
	 */
	public function getMaxJ($ts = NULL) {			
		return intval(date('j', $this -> getLdom($ts)));
	}
	
	/**
	 * แปลงค่า date/time string ให้เป็น timestamp
	 * รูปแบบคือ d/m/Y HH:mm หรือ m/d/Y HH:mm
	 *
	 * @param string $str
	 * @param bool $BEYear ปีอยู่ในรูปแบบ พศ. หรือไม่
	 * @return int
	 */
	public static function parse($str, $BEYear = true) {
		preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{2,4})\s*(\d{1,2})?\:?(\d{1,2})?\:?(\d{1,2})?/', $str, $matches);
		$yearOffset = $BEYear?DateUtil::AD2BE:0;
		if (DateUtil::DATEFORMAT == 'jnY') {
			$dIndex = 1;
			$mIndex = 2;
			$yIndex = 3;
		}
		else {
			$dIndex = 2;
			$mIndex = 1;
			$yIndex = 3;
		}
	
		if (count($matches) == 4) {
			return mktime(0, 0, 0, $matches[$mIndex], $matches[$dIndex], $matches[$yIndex] - $yearOffset);
		}
		else if(count($matches) >= 6) {
			$hh = $matches[4];
			$mm = $matches[5];
			$ss = $matches[6]?$matches[6]:0;
			return mktime($hh, $mm, $ss, $matches[$mIndex], $matches[$dIndex], $matches[$yIndex] - $yearOffset);
		}
		else
			return NULL;
	}
	
	/**
	 * Parse date string ใน ISO-8601 format
	 * @param string $str ค่าของ date string
	 * @return int
	 */
	public static function parseSqlDate($str) {
		if(is_null($str))
			return NULL;
		$dt = @strtotime($str);
	
		if ($dt === -1 || $dt === false)
			return NULL;
		else
			return $dt;
	}
	
	/**
	 * เปลี่ยน timestamp ให้เป็น string ภาษาไทยตาม format ที่ระบุ
	 * format ที่ใช้เหมือนกับฟังก์ชัน date() ปกติ
	 * @param string $format
	 * @param int $t
	 * @return string
	 */
	public static function th_date($format,$t=NULL)
	{		
		if (!is_numeric($t))
			return NULL;

		$output = '';

		$arrFormat = explode("\r\n",chunk_split($format,1));
		foreach ($arrFormat as $key => $val)
		{
			if(preg_match('/[aABdgGhHiIjLmnOrsStTUwWzZ]/',$val))
			{
				$output .= date($val,$t);
			}
			else
			{
				switch($val)
				{
					case 'D':		// Day of Week, short name
						$output .= self::$arrShortDoW[date('w',$t)];
						break;
					case 'F':		// Month, full name
						$output .= self::$arrMonthName[date('n',$t)];
						break;
					case 'l':			// Day of Week , long name
						$output .= self::$arrDoW[date('w',$t)];
						break;
					case 'M':		// Month, short name
						$output .= self::$arrShortMonthName[date('n',$t)];
						break;
					case 'Y':		// 4 digit Year
						$output .= date('Y',$t) + self::AD2BE;
						break;
					case 'y':		// 2 digit Year
						$output .= (date('y',$t) + 43) % 100;
						break;
					default:
							$output .= $val;
				}
			}
		}
		return $output;
	}
	
	/**
	 * หาค่า timestamp ของวันปัจจุบัน ณ เที่ยงคืน
	 *
	 * @return int
	 */
	public function today() {
		list($j, $n, $Y) = self::dateParts('jnY', time());
		return (mktime(0, 0, 0, $n, $j, $Y));
	}
	
	// constants
	const AD2BE = 543;
	const SECS_PER_DAY = 86400;
	const DATEFORMAT = 'jnY';
	
	const SD_FMT_FORM = 'j/n/Y';
	const SDT_FMT_FORM = 'j/n/Y H:i:s';	
	
	const SD_FMT_TH = 'j M y';
	const SD_FMTCUSTOM_TH = 'j M Y';
	const LD_FMT_TH = 'j F Y';
	const SDT_FMT_TH = 'j M y, H:i น.';
	const LDT_FMT_TH = 'j F Y, H:i น.';
	const ST_FMT_TH = 'H:i น.';
	const ST_FMT_TWOPOSITION_FORM = 'H:i';	
	
	const SQL_DT_FMT = 'Y-m-d H:i:s';
	const SQL_D_FMT = 'Y-m-d';
	
	public static $arrMonthName = array(1 => 'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
	public static $arrShortMonthName = array(1 => 'ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	public static $arrDoW = array('อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์');
	public static $arrShortDoW = array('อา','จ','อ','พ','พฤ','ศ','ส');
	
	public static $arrMonthNameEN = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	public static $arrShortMonthNameEN = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	public static $arrDoWEN = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	public static $arrShortDoWEN = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');	
}
?>