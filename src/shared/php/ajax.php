<?php

class AJAX
{
    public static function call(string $url, string $elementId): string
    {
        $generatedId = uniqid();
        $id = $elementId;

        $jsUrl = json_encode($url);
        $jsId = json_encode($id);
        $jsScriptId = "script_{$generatedId}";
        $jsScriptIdJson = json_encode($jsScriptId);

        return <<<HTML
        <div
            class="page"
            id="$id"
            hx-get="$url"
            hx-trigger="load"
            hx-swap="innerHTML"
            hx-on::response-error="this.innerHTML = '<p>Something went wrong.</p>'"
            hx-on::send-error="this.innerHTML = '<p>Something went wrong.</p>'"
        >
            <div class="spinner-overlay">
                <div class="spinner"></div>
            </div>
        </div>
        HTML;
    }
}
