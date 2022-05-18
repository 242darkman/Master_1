from pygame._sprite import Group


class WasteStack(Group):
    def __init__(self, rect, card_spacing):
        Group.__init__(self)
        self.rect = rect
        self.spacing = card_spacing

    def collidepoint(self, point):
        if self.sprites():
            return self.sprites()[-1].rect.collidepoint(point)
        else:
            return False

    def add_card(self, card):
        Group.add(self, card)
        self.update_cards_pos()

    def update_cards_pos(self):
        i = 0
        for card in self.sprites()[-3:]:
            card.rect.x = self.rect.x + self.spacing * i
            card.rect.y = self.rect.y
            i += 1

    def get_cards(self):
        cards = []
        for card in self.sprites():
            cards.append(card)
            self.remove(card)
        return cards

    def start_drag(self, mouse_pos):
        if self.sprites() and self.collidepoint(mouse_pos):
            card = self.sprites()[-1]
            self.remove(card)
            return [card]

        return []

    def end_drag(self):
        self.update_cards_pos()
        return

    def draw(self, surf):
        for card in self.sprites()[-3:]:
            surf.blit(card.image, card.rect)

    def drawCard(self, surf):
        if self.sprites():
            card = self.sprites()[-1]
            surf.blit(card.image, (self.rect.x, self.rect.y))
