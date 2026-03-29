# Czym jest Docker?

Docker to narzędzie, które służy do uruchamiania aplikacji w izolowanych środowiskach zwanych kontenerami. Pozwala spakować aplikację wraz z jej zależnościami i uruchamiać ją w spójny sposób na różnych maszynach.

## Czym jest kontener?

Kontener to lekkie, izolowane środowisko uruchomieniowe. Zawiera aplikację oraz wszystkie jej zależności, a przy tym współdzieli kernel systemu operacyjnego (najczęściej Linux). Dzięki temu kontenery są znacznie lżejsze niż tradycyjne maszyny wirtualne.

## Podstawy architektury Dockera

1. **Docker Engine** – to silnik Dockera, który działa jako daemon i zarządza kontenerami.
2. **Docker CLI** – narzędzie w terminalu, które pozwala sterować Dockerem.
3. **Obrazy (Images)** – to szablony kontenerów, zawierające aplikację i jej zależności.
4. **Kontenery** – to uruchomione instancje obrazów, które można uruchamiać i zatrzymywać.
5. **Docker Registry** – miejsce, w którym przechowywane są obrazy, np. Docker Hub.

## Kontener vs maszyna wirtualna

Kontenery są szybkie, lekkie i mniej zasobożerne, ponieważ dzielą kernel systemu. Maszyny wirtualne natomiast mają pełne systemy operacyjne, są cięższe, ale zapewniają większą izolację.

## Jak to działa pod spodem?

Docker wykorzystuje mechanizmy Linuxa, takie jak:
- **Namespaces** – zapewniają izolację procesów, sieci, plików.
- **cgroups** – kontrolują zasoby, takie jak CPU i RAM.

## Dlaczego Docker jest ważny?

Docker zapewnia:
- przenośność aplikacji,
- łatwe wdrożenia (CI/CD),
- skalowalność (np. w Kubernetes),
- izolację aplikacji.
