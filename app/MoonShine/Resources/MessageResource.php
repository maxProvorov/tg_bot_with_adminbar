<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;


use Illuminate\Database\Eloquent\Model;
use App\Models\Message;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo; 

/**
 * @extends ModelResource<Message>
 */
class MessageResource extends ModelResource
{
    protected string $model = Message::class;

    protected string $title = 'Messages';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                //ID::make()->sortable(),
                Text::make('Messages', 'message')->required(),
                Text::make('Created At', 'created_at')->readonly(),
                //Text::make('Telegram_id', 'telegram_id')->required(),
            ]),
        ];
    }

    /**
     * @param Message $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
