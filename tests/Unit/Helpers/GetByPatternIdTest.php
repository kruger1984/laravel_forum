<?php

declare(strict_types=1);

use App\Models\User;

test('getByPatternId extracts unique image ids from content', function (): void {
    $data = ['content' => 'Картинки img_id=12 и img_id=45'];

    $result = getByPatternId($data, '/img_id=[\d]+/', '/img_id=/');

    expect($result->toArray())->toEqual(['12', '45']);
});

test('getByPatternId removes duplicate ids from content', function (): void {
    $data = ['content' => 'Повтор: img_id=10 и img_id=10'];

    $result = getByPatternId($data, '/img_id=[\d]+/', '/img_id=/');

    expect($result->toArray())->toEqual(['10']);
});

test('getByPatternId returns empty collection when pattern not found', function (): void {
    $data = ['content' => '<p>Обычный текст без картинок</p>'];

    $result = getByPatternId($data, '/img_id=[\d]+/', '/img_id=/');

    expect($result)->toBeEmpty();
});

test('getCleanedUserId returns existing mentioned user ids', function (): void {
    $user = User::factory()->create();
    $data = ['content' => "Привет, @{$user->id}!"];

    $result = User::getCleanedUserId($data);

    expect($result->toArray())->toEqual([(string) $user->id]);
});

test('getCleanedUserId filters out non-existing user ids', function (): void {
    $data = ['content' => 'Обращение к @999999'];

    $result = User::getCleanedUserId($data);

    expect($result)->toBeEmpty();
});

test('getCleanedUserId handles multiple mentions and removes duplicates', function (): void {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $data = ['content' => "Ответ @{$user1->id}, @{$user2->id} и снова @{$user1->id}"];

    $result = User::getCleanedUserId($data);

    expect($result->values()->toArray())->toEqual([(string) $user1->id, (string) $user2->id]);
});
