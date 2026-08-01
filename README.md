# Jawbonez / Spot Pulse Control Plane

Jawbonez is the minimal-data control plane for the Spot Pulse local-first Windows application.

## Boundary

The server handles subscription entitlement, device public-key enrollment, signed cell manifests, immutable API-Code-Cell releases, and update delivery.

The server must not receive or store broker credentials, balances, positions, orders, fills, P&L, wallet state, or strategy state. Broker authentication and trade execution stay on the user's computer.

## Development status

The first implementation slice provides control-plane health, manifest comparison, immutable cell release downloads, device public-key enrollment, and a CLI cell publisher.

Existing `.env` files must never be overwritten. New settings belong in `.env.example` only.
