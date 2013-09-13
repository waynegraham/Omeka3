<?php
namespace Omeka\Api\Adapter;

/**
 * API adapter interface.
 */
interface AdapterInterface
{
    public function search();
    public function create();
    public function read();
    public function update();
    public function delete();
    public function setData(array $data);
    public function getData($key = null);
}