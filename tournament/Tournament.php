<?php

declare(strict_types=1);

class Tournament
{
    public function tally(string $scores): string
    {
        $output = "Team                           | MP |  W |  D |  L |  P";
        if (empty($scores)) { return $output; }
        $teams = $this->createTeams(explode("\n", $scores));
        $teams = $this->sortTeams($teams);
        return $this->buildOutput($teams, $output);
    }

    private function createTeams(array $scores): array
    {
        $teams = [];
        foreach ($scores as $score) {
            $fields = explode(";", $score);
            [$team, $opponent, $result] = $fields;
            $teams[$team] = $this->updateTeam($this->getTeam($teams, $team), $result);
            $teams[$opponent] = $this->updateTeam($this->getTeam($teams, $opponent), $this->reverseResult($result));
        }
        return $teams;
    }

    private function getTeam(array $teams, string $team): array
    {
        return $teams[$team] ?? [
            "Name" => $team,
            "MP" => 0,
            "W" => 0,
            "D" => 0,
            "L" => 0,
            "P" => 0,
        ];
    }

    private function updateTeam(array $team, string $result): array
    {
        $team["MP"] ++;
        switch($result) {
            case "win":
                $team["W"] ++;
                $team["P"] += 3;
                break;
            case "draw":
                $team["D"] ++;
                $team["P"] ++;
                break;
            case "loss":
                $team["L"] ++;
        }
        return $team;
    }

    private function reverseResult(string $result): string
    {
        return match ($result) {
            "win" => "loss",
            "loss" => "win",
            "draw" => "draw",
        };
    }

    private function sortTeams(array $teams): array
    {
        usort($teams, function($team, $opponent) {
            if ($team["P"] == $opponent["P"]) {
                return strcmp($team["Name"], $opponent["Name"]);
            }
            return $opponent["P"] - $team["P"];
        });
        return $teams;
    }

    private function buildOutput(array $teams, string $output): string {
        $length = strlen(explode("|", $output)[0]) - 1;
        foreach ($teams as $team) {
            [$name, $matches, $wins, $draws, $losses, $points] = [$team["Name"], $team["MP"], $team["W"], $team["D"], $team["L"], $team["P"]];
            $name = str_pad($name, $length);
            $output .= "\n$name |  $matches |  $wins |  $draws |  $losses |  $points";
        }
        return $output;
    }

}
