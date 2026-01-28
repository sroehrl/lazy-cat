<?php

namespace App\Mail\Services;

use Exception;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Neoan\Helper\Env;
use Neoan\Routing\Interfaces\Routable;

class Mail implements Routable
{
    private Client $mj;

    private array $body;

    private array $messageTemplate = [
        'To' => [],
    ];

    public function __construct(bool $sandbox = false)
    {
        $this->mj = new Client(Env::get('MAILJET_KEY'), Env::get('MAILJET_SECRET'), true, ['version' => 'v3.1']);

        $this->body = [
            'SandboxMode' => (bool) Env::get('MAILJET_SANDBOX', $sandbox),
            'Globals' => [
                'TemplateLanguage' => true,
                'Variables' => [],
                'From' => [
                    'Email' => Env::get('MAILJET_FROM_EMAIL'),
                    'Name' => Env::get('MAILJET_FROM_NAME'),
                ],
                'Subject' => '',
            ],
            'Messages' => [],
        ];
    }

    public function __invoke(): static
    {
        return $this;
    }

    public function addRecipient(string $email, string $name, array $variables = []): static
    {
        $fromTemplate = $this->messageTemplate;
        $fromTemplate['To'][] = [
            'Email' => $email,
            'Name' => $name,
        ];

        if (!empty($variables)) {
            $fromTemplate['Variables'] = $variables;
        }

        $this->body['Messages'][] = $fromTemplate;

        return $this;
    }

    public function setFrom(string $email, string $name): static
    {
        $this->body['Globals']['From']['Email'] = $email;
        $this->body['Globals']['From']['Name'] = $name;
        return $this;
    }

    public function setSubject(string $subject): static
    {
        $this->body['Globals']['Subject'] = $subject;

        return $this;
    }

    public function setHtml(string $html): static
    {
        $this->body['Messages'][0]['HTMLPart'] = $html;
        return $this;
    }

    public function setText(string $text): static
    {
        $this->body['Messages'][0]['TextPart'] = $text;
        return $this;
    }

    public function setTemplate(int $templateId): static
    {
        $this->body['Globals']['TemplateID'] = $templateId;
        $this->body['Globals']['TemplateLanguage'] = true;
        $this->body['Globals']['Variables'] = [];

        return $this;
    }

    public function setVariables(array $variables): static
    {
        $this->body['Globals']['Variables'] = [...$this->body['Globals']['Variables'], ...$variables];

        return $this;
    }

    public function setVariable(string $key, string $value): static
    {
        $this->body['Globals']['Variables'][$key] = $value;

        return $this;
    }

    public function addFileAttachment(string $path): static
    {
        if (!isset($this->body['Globals']['Attachments'])) {
            $this->body['Globals']['Attachments'] = [];
        }
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $filename = $parts[count($parts) - 1];
        $this->body['Globals']['Attachments'][] = [
            'ContentType' => match (explode('.', $filename)[1]) {
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                'zip' => 'application/zip',
                'txt' => 'text/plain',
            },
            'Filename' => $filename,
            'Base64Content' => base64_encode(file_get_contents($path))
        ];
        return $this;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @throws Exception
     */
    public function send(): Response
    {
        $body = $this->getBody();


        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()) {

            throw new Exception($response->getReasonPhrase());
        }


        return $response;
    }

}