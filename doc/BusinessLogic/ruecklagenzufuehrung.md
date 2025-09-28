# Rücklagenzuführung (Reserve Contribution) Treatment

## Current Implementation:
- RÜCKLAGENZUFÜHRUNG (-800,00 €) is included in GESAMTSUMME ALLER KOSTEN
- It reduces the total from 25,276.81 € to 24,476.81 €
- This is treated as a "negative cost" that reduces the total burden

## Two Possible Approaches:

**Option A: Include it (current approach)**
- Treats reserve contributions as a "negative cost" that reduces the total burden
- Logic: Money going to reserves benefits owners and reduces current year's net costs
- Result: Lower ABRECHNUNGS-SALDO for each owner
- **This is the standard approach in German WEG accounting**

**Option B: Exclude it**
- Treats reserve contributions as a separate financial movement, not a cost reduction
- Logic: Reserves are still the owners' money, just set aside for future use
- Result: Higher ABRECHNUNGS-SALDO for each owner

## Why Current Approach is Correct:
In German WEG accounting, **Option A (current approach) is typically correct** because:
1. Reserve contributions are part of the annual financial plan (Wirtschaftsplan)
2. They're included in the Hausgeld calculations
3. They represent money that stays within the WEG for future maintenance
4. The negative amount indicates money flowing INTO reserves (reducing current costs)

## Alternative Display Format (if needed):
```
Umlagefähige Kosten:        14,453.17 €
Nicht umlagefähige Kosten:  10,823.64 €
----------------------------------------
Zwischensumme Kosten:       25,276.81 €
Rücklagenzuführung:           -800.00 €
----------------------------------------
GESAMTSUMME ALLER KOSTEN:   24,476.81 €
```