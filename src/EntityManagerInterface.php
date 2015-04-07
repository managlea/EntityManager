<?php

namespace Managlea\Component;


interface EntityManagerInterface
{
    public function getRepository();
    
    public function persist();
    
    public function remove();
    
    public function flush();
}
