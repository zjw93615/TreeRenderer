<?php

class TreeRenderer {

    private $children = [];
    private $data = [];
    private static $_instance = null;


    public function __construct(  ) {
        $handle = fopen("input_file.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // process the line read.
                $temp = explode("|",$line);
                $node = array(
                    'uid' => $temp[0],
                    'pid' => $temp[1],
                    'name' => $temp[2],
                    'sort' => $temp[3],
                );
                $this->children[$node['pid']][] = $node;
            }

            fclose($handle);

            // 每个父母节点独立排序
            foreach ($this->children as &$value) {
                usort($value, function ($a, $b) {
                    if($a['sort'] == $b['sort']) {
                        return $a['uid'] - $b['uid'];
                    }
                    return $a['sort'] - $b['sort'];
                });
            }

            unset($value); // 最后取消掉引用

        } else {
            // error opening the file.
            echo "error while opening file";
        }
    }

    public static function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function render($current_id = 0) {
        foreach ($this->children[$current_id] as $child) {
            echo "<div class='tree-node'>";

            // 判断是否为第一个节点，控制输出
            if($current_id == 0) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $child['name'];
            }else {
                echo "|--->" . $child['name'];
            }

            // 递归输出之后的节点
            if(array_key_exists($child['uid'], $this->children)) {
                $this->render($child['uid']);
            }
            echo "</div>";
        }
    }
}