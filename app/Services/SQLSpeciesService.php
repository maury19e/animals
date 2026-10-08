<?php

namespace App\Services;

use App\Contracts\SpeciesServiceInterface;
use App\Exceptions\SpeciesNotFoundException;
use Illuminate\Support\Facades\DB;

class SQLSpeciesService implements SpeciesServiceInterface
{
    public function all(): array
    {
        return array_map(
            fn ($species) => (array) $species,
            DB::select('SELECT * FROM species')
        );
    }
    public function find(string $id): ?array
    {
        $species = DB::select(
            'SELECT * FROM species WHERE id = ?',
            [$id]
        );

        if (empty($species)) {
            throw SpeciesNotFoundException::forId($id);
        }

        return (array) $species[0];
    }
    public function create(array $data): array
    {
        DB::insert(
            'INSERT INTO species (name) VALUES (?)',
            [$data['name']]
        );

        $id = DB::getPdo()->lastInsertId();

        return $this->find($id);
    }
    public function update(string $id, array $data): ?array
    {
        $this->find($id);

        DB::update(
            'UPDATE species SET name = ? WHERE id = ?',
            [$data['name'], $id]
        );

        return $this->find($id);
    }
    public function delete(string $id): bool
    {
        $this->find($id);

        return DB::delete(
            'DELETE FROM species WHERE id = ?',
            [$id]
        ) > 0;
    }
    public function reset(): void
    {
        DB::delete('DELETE FROM species');
    }
}