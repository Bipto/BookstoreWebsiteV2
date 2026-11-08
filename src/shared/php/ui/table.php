
<?php

readonly class TableRow
{
    public function __construct(public array $data, public ?string $action = null) {}
}

class Table
{
    private array $headers = [];
    private array $rows = [];
    private string $tableClass = 'table';

    public function __construct(array $headers = [], array $rows = [])
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    // Set table CSS class
    public function setClass(string $class): self
    {
        $this->tableClass = $class;
        return $this;
    }

    // Add a column heading
    public function addHeader(string $header): self
    {
        $this->headers[] = $header;
        return $this;
    }

    // Add a row of data
    public function addRow(array $row): self
    {
        $this->rows[] = $row;
        return $this;
    }

    // Generate the HTML table
    public function render(): string
    {
        $html = '<div class="table-container">';

        $html .= '<table class="' .
            htmlspecialchars($this->tableClass, ENT_QUOTES, 'UTF-8') .
            '">';

        if (!empty($this->headers)) {
            $html .= '<thead><tr>';

            foreach ($this->headers as $header) {
                $html .= '<th>' .
                    htmlspecialchars((string)$header, ENT_QUOTES, 'UTF-8') .
                    '</th>';
            }

            $html .= '</tr></thead>';
        }

        $html .= '<tbody>';

        foreach ($this->rows as $row) {
            $action = $row['action'] ?? null;

            if ($action) {
                $html .= "<tr onclick='{$row['action']}'>";
            } else {
                $html .= "<tr>";
            }


            foreach ($row['data'] as $cell) {
                $html .= '<td>' .
                    htmlspecialchars((string)($cell ?? ''), ENT_QUOTES, 'UTF-8') .
                    '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }
}
