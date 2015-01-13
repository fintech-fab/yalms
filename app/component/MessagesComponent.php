<?php
namespace Yalms\Component;

/**
 * Класс для служебных сообщений(оповещений)
 * о результате выполнения типичных операций
 */
class MessagesComponent
{
    /**
     * Задает статусное сообщение,был ли создан объект или нет
     * @param $instance
     * @param bool $result
     * @param bool $customName
     */
    public function setMessageCreateObject($instance, $result, $customName = false)
    {
        $templateMessage = ' been created';//По умолчанию мы счтаем что объект был создан
        $instance->message = $this->getStatusMessage($instance, $result,$customName, $templateMessage);
    }

    /**
     * Задает статусное сообщение,был ли обновлен объект или нет
     * @param $instance
     * @param bool $result
     * @param bool $customName
     */
    public function setMessageUpdateObject($instance, $result, $customName = false)
    {
        $templateMessage = ' been updated';//По умолчанию мы счтаем что объект был обновлен
        $instance->message = $this->getStatusMessage($instance, $result, $customName, $templateMessage);
    }

    /**
     * Задает статусное сообщение,был ли удален объект или нет.
     * @param $instance
     * @param bool $result
     * @param bool $customName
     */
    public function setMessageDeleteObject($instance, $result, $customName = false)
    {
        $templateMessage = ' been deleted';//По умолчанию мы счтаем что объект был удален
        $instance->message = $this->getStatusMessage($instance, $result, $customName, $templateMessage);
    }


    //Вспомогательные функции класса
    /**
     * Функция принимают инстанс класса($instance)
     * и результат выполнения операции($result)
     * над которым совершается действие
     * Инстанс должен иметь поле(атрибут)
     * в который и будет помещено сообщение.
     * По умолчанию создается сообщение
     * с использованием имени класса инстанцируемого объекта,
     * но можно задать свое значение($customName),
     * в этом случае стандартное сообщение будет подменено
     * @param $instance
     * @param $customName
     * @param bool $result
     * @param $templateMessage
     * @return string
     */
    private function getStatusMessage($instance, $result, $customName, $templateMessage)
    {

        if ($result == false) {
            //Если результат операции  неудачен - инвертируем шаблон
            $templateMessage = $this->invertTemplate($templateMessage);
        }
        //Возможно кто то захочет не стандартное сообщение вида "Bla-Bla been created"
        //а что то иное вида "Super Bla-Bla-Bla with mega-attributes been created",
        //тогда он может передать кастомный параметр $customName ("Super Bla-Bla-Bla with mega-attributes")
        if ($customName != false) {
            //Пришли дополнительные параметры и надо показать что то нестандартное
            $templateMessage = $customName . $templateMessage;
        } else {
            $className = get_class($instance);
            $templateMessage = $className . $templateMessage;
        }

        return $templateMessage;
    }

    /**
     * Инвертирует(дает отрицательный смысл входщему шаблону сообщения
     * @param $templateMessage
     * @return string
     */
    private function invertTemplate($templateMessage)
    {
        return ' not' . $templateMessage;
    }

    /**
     * Принимает инстанс объекта(instance) над которым проводилась операция
     * и булево значение итога операции(result)
     * в результате его формирует статус операции(fail/success)
     * Инстанс должен иметь атрибут status для присовения статуса операции
     * @param $instance
     * @param $result
     */
    static public function operationStatus($instance, $result)
    {
        if ($result == true)
            $status = 'success';
        else
            $status = 'fail';

        $instance->status = $status;
    }

}