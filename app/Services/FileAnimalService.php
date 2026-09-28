<?php

namespace App\Services;

use App\Contracts\AnimalServiceInterface;
use Illuminate\Support\Facades\Storage;

class FileAnimalService implements AnimalServiceInterface
{
    private function getAnimals(): array
    {
        if (!Storage::disk('local')->exists('animals.json')) {
            return [];
        }

        $json = Storage::disk('local')->get('animals.json');

        return json_decode($json, true) ?? [];
    }

    private function saveAnimals(array $animals): void
    {
        $json = json_encode($animals, JSON_PRETTY_PRINT);

        Storage::disk('local')->put('animals.json', $json);
    }
    public function all(): array
    {
        return $this->getAnimals();
    }
    public function find(string $id): ?array
    {
        $animals = $this->getAnimals();

        if (!isset($animals[$id])) {
            throw new \Exception("El animal con ID {$id} no fue encontrado.");
        }

        return ['id' => $id] + $animals[$id];
    }

    public function create(array $data): array
    {
        $animals = $this->getAnimals();

        $newId = empty($animals)
            ? 1
            : max(array_map('intval', array_keys($animals))) + 1;

        $animals[$newId] = $data;

        $this->saveAnimals($animals);

        return ['id' => $newId] + $data;
    }
    public function update(string $id, array $data): ?array
    {
        $animals = $this->getAnimals();

        if (!isset($animals[$id])) {
            throw new \Exception("El animal con ID {$id} no fue encontrado.");
        }

        $animals[$id] = $data;

        $this->saveAnimals($animals);

        return ['id' => $id] + $data;
    }
    public function delete(string $id): bool
    {
        $animals = $this->getAnimals();

        if (!isset($animals[$id])) {
            throw new \Exception("El animal con ID {$id} no fue encontrado.");
        }

        unset($animals[$id]);

        $this->saveAnimals($animals);

        return true;
    }
    public function reset(): void
    {
        $this->saveAnimals([]);
    }
}