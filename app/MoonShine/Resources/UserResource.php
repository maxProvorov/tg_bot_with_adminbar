<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Text;
use MoonShine\Fields\Enum;
use MoonShine\Fields\Relationships\HasMany;
use App\Enums\StatusEnum;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Models\TelegraphBot;

/**
 * @extends ModelResource<User>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Users';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Nickname', 'nickname')->required(),
                Text::make('Phone', 'phone')->required(),
                Enum::make('Status', 'status')->attach(StatusEnum::class)->required(),
                Text::make('Telegram_id', 'telegram_id')->required(),
                HasMany::make('Messages', 'messages', MessageResource::class),
            ]),
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }

    protected function afterUpdated(Model $item): Model
    {
        $this->notifyUserStatusChange($item);
        return $item;
    }

    private function notifyUserStatusChange(Model $user)
    {
        $chat_id = (int) $user->telegram_id;
        $statusMessage = $this->getStatusMessage($user->status);
        $this->sendMessageToTelegram($chat_id, $statusMessage);
    }

    private function getStatusMessage(string $status): string
    {
        $messages = [
            'active' => 'Ваш статус обновлен на активный.',
            'blocked' => 'Ваша учетная запись заблокирована.',
        ];

        return $messages[$status];
    }

    private function sendMessageToTelegram(int $chat_id, string $message)
    {
        $bot = TelegraphBot::first();
        $chat = $bot->chats()->where('chat_id', $chat_id)->first();
        $chat->message($message)->send();
    }
}
