<?php

/**
 * Use this file to define all hooks
 **

/**
 * POINTS FLOW
 **

START -> Bootstrap -> URI -> Route -> Controller -> View -> END

/**
 * AVAILABLE POINTS
 **

dpx_post_start      - Right after calling the bootstrap class.
dpx_pre_route       - Right after URI class but before routing the request.
dpx_pre_controller  - Right after routing the request but before calling the controller.
dpx_pre_model       - Right before calling the first model.
dpx_pre_view        - Right before calling the first view.
dpx_pre_end         - Before ending the execution

/**
 * USAGE
 **

Hooks::add('Point Name','File_Path','File Name','Class Name','Method (optional)','Parameters (optional)');

 */

Hooks::add('dpx_post_start','hooks','ProfilerHook','ProfilerHook','start');
Hooks::add('dpx_pre_controller','hooks','ProfilerHook','ProfilerHook','start','controller');
Hooks::add('dpx_pre_end','hooks','ProfilerHook','ProfilerHook','stop');
Hooks::add('dpx_pre_end','hooks','ProfilerHook','ProfilerHook','stop','controller');
Hooks::add('dpx_pre_end','hooks','ProfilerHook','ProfilerHook','display');

?>