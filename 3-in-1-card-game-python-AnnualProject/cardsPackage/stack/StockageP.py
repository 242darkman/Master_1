from pygame._sprite import Group


class StockageStack(Group):
    def __init__(self, rect, image):
        Group.__init__(self)
        self.rect = rect
        self.image = image

    def collidepoint(self, point):
        return self.rect.collidepoint(point)

    def add_card(self, card):
        card.rect.x = self.rect.x
        card.rect.y = self.rect.y

        Group.add(self, card)

    def get_cards(self):
        card = self.sprites()[0]
        self.remove(card)
        return [card]

    def get_3_cards(self):
        card = self.sprites()[:3]
        self.remove(card)
        return [card]

    def is_empty(self):
        return not self.sprites()

    def draw(self, surf):
        if not self.sprites():
            surf.blit(self.image, (self.rect.x, self.rect.y))
        else:
            surf.blit(self.sprites()[0].image, (self.rect.x, self.rect.y))
