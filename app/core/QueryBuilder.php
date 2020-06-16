<?php

namespace app\core;

class QueryBuilder
{
    private $from;
    private $columns;
    private $distinct = false;
    private $joins;
    private $wheres;

    private $groups;
    private $havings;
    private $orders;
    private $limit;
    private $offset;

    public function __construct($table)
    {
        $this->from = $table;
    }

    public static function table($table)
    {
        return new self($table);
    }

    public function select($columns)
    {
        $this->columns = is_array($columns) ? $columns : func_get_args($columns);
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = "inner")
    {
        $this->joins[] = array($table, $first, $operator, $second, $type);
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        $this->join($table, $first, $operator, $second, "left");
        return $this;
    }

    public function rightJoin($table, $first, $operator, $second)
    {
        $this->join($table, $first, $operator, $second, "right");
        return $this;
    }

    public function where($column, $operator, $value, $boolean = "AND")
    {
        $this->wheres[] = array($column, $operator, $value, $boolean);
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        $this->where($column, $operator, $value, "OR");
        return $this;
    }

    public function groupBy($columns)
    {
        $this->groups = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function having($column, $operator, $value, $boolean = "AND")
    {
        $this->havings[] = array($column, $operator, $value, $boolean);
        return $this;
    }

    public function orHaving($column, $operator, $value)
    {
        $this->having($column, $operator, $value, "OR");
        return $this;
    }

    public function orderBy($column, $direction = "asc")
    {
        $this->orders[] = array($column, $direction);
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function get()
    {
        if (empty($this->from)) {
            return false;
        }
        $sql = $this->distinct ? "SELECT DISTINCT " : "SELECT ";
        if (isset($this->columns) && is_array($this->columns)) {
            $sql .= implode(", ", $this->columns);
        } else {
            $sql = "*";
        }
        $sql .= " FROM {$this->from} ";
        if (isset($this->joins) && is_array($this->joins)) {
            foreach ($this->joins as $join) {
                switch (strtolower($join[4])) {
                    case 'inner':
                        $sql .= "INNER JOIN ";
                        break;
                    case 'left':
                        $sql .= "LEFT JOIN ";
                        break;
                    case 'right':
                        $sql .= "RIGHT JOIN ";
                        break;

                    default:
                        $sql .= "INNER JOIN ";
                        break;
                }
                $sql .= " {$join[0]} ON {$join[1]} {$join[2]} {$join[3]} ";
            }
        }

        if (isset($this->wheres) && is_array($this->wheres)) {
            $sql .= " WHERE 1=1 ";
            foreach ($this->wheres as $where) {
                $sql .= " {$where[3]} {$where[0]} {$where[1]} {$where[2]} ";
            }
        }

        if (isset($this->groups) && is_array($this->groups)) {
            $sql .= " GROUP BY " . implode(", ", $this->groups);
            if (isset($this->havings) && is_array($this->havings)) {
                $sql .= " HAVING 1=1 ";
                foreach ($this->havings as $havings) {
                    $sql .= " {$havings[3]} {$havings[0]} {$havings[1]} {$havings[2]} ";
                }
            }
        }

        if (isset($this->orders) && is_array($this->orders)) {
            $sql .= " ORDER BY ";
            $orderBy = array();
            foreach ($this->orders as $item) {
                $orderBy[] = "{$item[0]} {$item[1]}";
            }
            $sql .= implode(", ", $orderBy);
        }

        if (!empty($this->limit)) {
            $sql .= " LIMIT {$this->limit}";
        }

        if (!empty($this->offset)) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

}
