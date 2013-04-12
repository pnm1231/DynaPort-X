<?php

class ProfilerHook {

    function start($component='general'){
        Profiler::timerStart($component);
    }
    
    function stop($component='general'){
        Profiler::timerStop($component);
    }
    
    function display(){
        echo '<hr>';
        echo 'Controller : ';
        Profiler::timerDisplay('controller');
        echo '<br>';
        echo 'Total : ';
        Profiler::timerDisplay();
        echo '<br>';
        echo 'Memory : ';
        Profiler::memoryDisplay();
        echo '<br>';
        echo 'Files : <pre>';
        print_r(get_included_files());
    }

}

?>