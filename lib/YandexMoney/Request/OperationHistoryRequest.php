<?php

namespace YandexMoney\Request;


use YandexMoney\Presets\ApiKey;

class OperationHistoryRequest extends BaseRequest
{

    /**
     * @param mixed $details
     */
    public function setDetails($details)
    {
        $this->paramsArray[ApiKey::DETAILS] = $details;
    }

    /**
     * @return boolean
     */
    public function getDetails()
    {
        return $this->checkAndReturn(ApiKey::DETAILS);
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->paramsArray[ApiKey::FROM] = $from;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->checkAndReturn(ApiKey::FROM);
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->paramsArray[ApiKey::LABEL] = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->checkAndReturn(ApiKey::LABEL);
    }

    /**
     * @param int $records
     */
    public function setRecords($records)
    {
        $this->paramsArray[ApiKey::RECORDS] = $records;
    }

    /**
     * @return int
     */
    public function getRecords()
    {
        return $this->checkAndReturn(ApiKey::RECORDS);
    }

    /**
     * @param mixed $startRecord
     */
    public function setStartRecord($startRecord)
    {
        $this->paramsArray[ApiKey::START_RECORD] = $startRecord;
    }

    /**
     * @return mixed
     */
    public function getStartRecord()
    {
        return $this->checkAndReturn(ApiKey::START_RECORD);
    }

    /**
     * @param mixed $till
     */
    public function setTill($till)
    {
        $this->paramsArray[ApiKey::TILL] = $till;
    }

    /**
     * @return mixed
     */
    public function getTill()
    {
        return $this->checkAndReturn(ApiKey::TILL);
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->paramsArray[ApiKey::TYPE] = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->checkAndReturn(ApiKey::TYPE);
    }

} 