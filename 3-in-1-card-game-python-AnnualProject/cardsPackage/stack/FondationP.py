from pygame._sprite import Group


class FoundationStack(Group):
    def __init__(self, rect, image):
        Group.__init__(self)
        self.rect = rect
        self.image = image
        self.suit = None

    def add_card(self, card):
        card.rect.x = self.rect.x
        card.rect.y = self.rect.y

        Group.add(self, card)

    def collidepoint(self, pos):
        return self.rect.collidepoint(pos)

    def start_drag(self, mouse_pos):
        if self.sprites() and self.collidepoint(mouse_pos):
            card = self.sprites()[-1]
            self.remove(card)
            return [card]
        else:
            return []

    def end_drag(self):
        return

    def drop(self, card):
        can_drop = False

        if not self.sprites() and card.value == 1:
            self.suit = card.suit
            can_drop = True
        elif self.suit == card.suit and self.sprites()[-1].value + 1 == card.value:
            can_drop = True

        if can_drop:
            self.add_card(card)

        return can_drop

    def draw(self, surf):
        if not self.sprites():
            surf.blit(self.image, (self.rect.x, self.rect.y))
        else:
            surf.blit(self.sprites()[-1].image, (self.rect.x, self.rect.y))
